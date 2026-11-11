<?php

namespace App\Http\Controllers;

use App\Models\Requestor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Exports a Creative Team archive (layout, production, UZ Post, UZ PR) to an
 * Excel file, using the same layout as the Social Media and Photography exports.
 *
 *   GET /layoutservices/archive/export?month=11&year=2026
 *
 * The service is picked from the route default. Filters are the same as the
 * dashboard's Archived tab: the tab's statuses plus the selected month/year.
 */
class CreativeArchiveExportController extends Controller
{
    private const STATUSES = [
        0 => 'Pending',
        1 => 'Approve',
        2 => 'In Progress',
        3 => 'Rejected',
        4 => 'For Approval',
    ];

    private const SDG_MAP = [
        '1'  => 'No Poverty',
        '2'  => 'Zero Hunger',
        '3'  => 'Good Health and Well-being',
        '4'  => 'Quality Education',
        '5'  => 'Gender Equality',
        '6'  => 'Clean Water and Sanitation',
        '7'  => 'Affordable and Clean Energy',
        '8'  => 'Decent Work and Economic Growth',
        '9'  => 'Industry, Innovation and Infrastructure',
        '10' => 'Reduced Inequalities',
        '11' => 'Sustainable Cities and Communities',
        '12' => 'Responsible Consumption and Production',
        '13' => 'Climate Action',
        '14' => 'Life Below Water',
        '15' => 'Life on Land',
        '16' => 'Peace, Justice and Strong Institutions',
        '17' => 'Partnerships for the Goals',
    ];

    /** Per-service archive definition, mirroring each dashboard's Archived tab. */
    private const SERVICES = [
        'layout' => [
            'label'         => 'Layout Services',
            'sheet'         => 'Layout Archive',
            'file'          => 'Layout',
            'specification' => 'layout',
            'statuses'      => [1, 4],
        ],
        'production' => [
            'label'         => 'Production Services',
            'sheet'         => 'Production Archive',
            'file'          => 'Production',
            'specification' => 'production',
            'statuses'      => [1, 4],
        ],
        'uzpost' => [
            'label'         => 'UZ Post',
            'sheet'         => 'UZ Post Archive',
            'file'          => 'UZ-Post',
            'specification' => 'uzpost',
            'statuses'      => [2],
        ],
        'uzpr' => [
            'label'         => 'UZ PR',
            'sheet'         => 'UZ PR Archive',
            'file'          => 'UZ-PR',
            'specification' => 'uzpr',
            'statuses'      => [2],
        ],
    ];

    public function __invoke(Request $request)
    {
        $config = self::SERVICES[$request->route('service')] ?? null;
        abort_if($config === null, 404);

        $data = $request->validate([
            'month' => ['nullable', 'regex:/^(0?[1-9]|1[0-2])$/'], // 1-12, "01" is fine
            'year'  => ['nullable', 'digits:4'],
        ]);

        $month = isset($data['month']) ? (int) $data['month'] : null;
        $year  = isset($data['year'])  ? (int) $data['year']  : null;

        $constrain = function ($q) use ($config, $month, $year) {
            $q->where('specification', $config['specification'])
                ->whereIn('status', $config['statuses'])
                ->when($year, fn ($q) => $q->whereYear('created_at', $year))
                ->when($month, fn ($q) => $q->whereMonth('created_at', $month));
        };

        $rows = Requestor::with(['publishing' => $constrain, 'publishing.user:employee_id,fullname'])
            ->whereHas('publishing', $constrain)
            ->get()
            ->sortBy(fn ($r) => $r->publishing->created_at)
            ->values();

        $monthName = $month ? Carbon::create(2000, $month, 1)->format('F') : null;
        $period    = $this->periodLabel($monthName, $year);

        $spreadsheet = $this->buildSpreadsheet($rows, $config, $period);

        $fileName = $config['file'] . '_Archive_' . $this->fileSuffix($monthName, $year) . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /* ---------------------------------------------------------------------
     | Spreadsheet
     | ------------------------------------------------------------------- */

    private function buildSpreadsheet($rows, array $config, string $period): Spreadsheet
    {
        $headers = [
            'No.', 'Request ID', 'Requestor', 'Email', 'Department',
            'Purpose', 'Category', 'Section', 'Date of Event', 'Date Needed',
            'Reference', 'Size', 'Quantity', 'Material', 'SDG',
            'Content Information', 'Approve By', 'Date Approved', 'Assigned To',
            'Date Requested', 'Date Published', 'Status',
        ];
        $lastCol = $this->col(count($headers));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($config['sheet']);

        // ----- Title block -----
        $this->setText($sheet, 'A1', $config['label'] . ' - Archive');
        $this->setText($sheet, 'A2', 'Period: ' . $period . '   |   Generated: ' . now()->format('M d, Y h:i A'));
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setItalic(true)->getColor()->setRGB('666666');

        // ----- Header row -----
        $headerRow = 4;
        foreach ($headers as $i => $label) {
            $this->setText($sheet, $this->col($i + 1) . $headerRow, $label);
        }
        $sheet->getStyle("A{$headerRow}:{$lastCol}{$headerRow}")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2F5597']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(24);

        // ----- Data rows -----
        $r = $headerRow + 1;

        if ($rows->isEmpty()) {
            $this->setText($sheet, "A{$r}", 'No archived requests found for this period.');
            $sheet->mergeCells("A{$r}:{$lastCol}{$r}");
            $sheet->getStyle("A{$r}")->getFont()->setItalic(true);
        }

        foreach ($rows as $i => $req) {
            $info = $req->publishing;

            $category = $info->category;
            if ($info->other_category && in_array(strtolower((string) $category), ['other', 'others'], true)) {
                $category = $info->other_category;
            }

            $values = [
                $i + 1,
                $info->request_id,
                $req->fullname,
                $req->email,
                $req->department,
                $info->purpose,
                $category,
                $info->section,
                $this->formatDate($info->date_event),
                $this->formatDate($info->date_needed),
                $info->reference,
                $info->size,
                $info->quantity,
                $info->type_material,
                $this->sdgLabel($info->sdg),
                $info->content_information,
                $info->approve,
                $this->formatDate($info->date_approved),
                $info->user?->fullname ?? ($info->produce ?: 'Unassigned'),
                $info->created_at?->format('M d, Y h:i A'),
                $this->formatDate($info->published),
                self::STATUSES[(int) $info->status] ?? 'Unknown',
            ];

            foreach ($values as $c => $value) {
                $this->setText($sheet, $this->col($c + 1) . $r, $value);
            }
            $r++;
        }

        $lastRow = max($r - 1, $headerRow);

        // ----- Formatting -----
        $sheet->getStyle("A{$headerRow}:{$lastCol}{$lastRow}")->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('D9D9D9');
        $sheet->getStyle("A" . ($headerRow + 1) . ":{$lastCol}{$lastRow}")
            ->getAlignment()->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);

        $widths = [6, 16, 24, 30, 22, 36, 18, 18, 16, 16, 30, 10, 10, 18, 40, 40, 20, 16, 24, 22, 16, 12];
        foreach ($widths as $i => $w) {
            $sheet->getColumnDimension($this->col($i + 1))->setWidth($w);
        }

        $sheet->freezePane('A' . ($headerRow + 1));
        $sheet->setAutoFilter("A{$headerRow}:{$lastCol}{$lastRow}");

        $sheet->getPageSetup()->setOrientation('landscape');
        $sheet->getPageSetup()->setFitToWidth(1)->setFitToHeight(0);

        return $spreadsheet;
    }

    private function sdgLabel($sdg): string
    {
        $ids = array_filter((array) ($sdg ?? []), fn ($id) => $id !== '' && $id !== null);

        return implode(', ', array_map(
            fn ($id) => $id . '. ' . (self::SDG_MAP[(string) $id] ?? 'Unknown'),
            $ids
        ));
    }

    /**
     * Always write cells as plain text. Request data comes from a public form, so a
     * value like "=HYPERLINK(...)" must never be treated as a formula by Excel.
     * (Numbers such as the row counter are left as numbers.)
     */
    private function setText($sheet, string $cell, $value): void
    {
        if (is_int($value) || is_float($value)) {
            $sheet->setCellValue($cell, $value);
            return;
        }

        $sheet->setCellValueExplicit($cell, (string) ($value ?? ''), DataType::TYPE_STRING);
    }

    private function formatDate($value): string
    {
        if (empty($value)) {
            return '';
        }

        try {
            return Carbon::parse($value)->format('M d, Y');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }

    /** 1 => A, 2 => B, ... 27 => AA */
    private function col(int $index): string
    {
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index);
    }

    /* ---------------------------------------------------------------------
     | Labels / file name
     | ------------------------------------------------------------------- */

    private function periodLabel(?string $monthName, ?int $year): string
    {
        return match (true) {
            $monthName && $year => "{$monthName} {$year}",
            (bool) $monthName   => "{$monthName} (all years)",
            (bool) $year        => "All months {$year}",
            default             => 'All records',
        };
    }

    private function fileSuffix(?string $monthName, ?int $year): string
    {
        return match (true) {
            $monthName && $year => "{$monthName}_{$year}",
            (bool) $monthName   => "{$monthName}_All-Years",
            (bool) $year        => (string) $year,
            default             => 'All',
        };
    }
}
