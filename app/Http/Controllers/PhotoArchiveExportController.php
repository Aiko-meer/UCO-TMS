<?php

namespace App\Http\Controllers;

use App\Models\Photo_vid_request_informations;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Exports photography requests to an Excel file, using the same layout as the
 * Social Media archive export.
 *
 *   GET /photography/export?month=11&year=2026&status=2
 *
 * "month" and "year" filter on the date the request was created. "status" is
 * optional; the archive button passes 2 (Done).
 */
class PhotoArchiveExportController extends Controller
{
    private const STATUSES = [
        0 => 'Pending',
        1 => 'Approve',
        2 => 'Done',
        3 => 'Rejected',
    ];

    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'month'  => ['nullable', 'regex:/^(0?[1-9]|1[0-2])$/'], // 1-12, "01" is fine
            'year'   => ['nullable', 'digits:4'],
            'status' => ['nullable', 'integer', 'in:0,1,2,3'],
        ]);

        $month  = isset($data['month'])  ? (int) $data['month']  : null;
        $year   = isset($data['year'])   ? (int) $data['year']   : null;
        $status = isset($data['status']) ? (int) $data['status'] : null;

        $rows = Photo_vid_request_informations::with([
                'request:request_id,f_name,l_name,middle,email,department',
                'user:employee_id,fullname',
            ])
            ->when($status !== null, fn ($q) => $q->where('status', $status))
            ->when($year, fn ($q) => $q->whereYear('created_at', $year))
            ->when($month, fn ($q) => $q->whereMonth('created_at', $month))
            ->get()
            ->sortBy('created_at')
            ->values();

        $monthName   = $month ? Carbon::create(2000, $month, 1)->format('F') : null;
        $period      = $this->periodLabel($monthName, $year);
        $statusLabel = $status !== null ? (self::STATUSES[$status] ?? 'Status') : null;

        $spreadsheet = $this->buildSpreadsheet($rows, $period, $statusLabel);

        $fileName = 'Photo_Archive_' . $this->fileSuffix($monthName, $year) . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /* ---------------------------------------------------------------------
     | Spreadsheet
     | ------------------------------------------------------------------- */

    private function buildSpreadsheet($rows, string $period, ?string $statusLabel): Spreadsheet
    {
        $headers = [
            'No.', 'Request ID', 'Requestor', 'Email', 'Department',
            'Category', 'Event', 'Section', 'Venue', 'Equipment',
            'Purpose', 'Date Needed', 'Start Time', 'End Time',
            'Approved By', 'Assigned To', 'Date Requested', 'Date Published', 'Status',
        ];
        $lastCol = $this->col(count($headers));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Photography Archive');

        // ----- Title block -----
        $title = 'Photography Requests - ' . ($statusLabel ? $statusLabel . ' Archive' : 'Archive');
        $this->setText($sheet, 'A1', $title);
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
            $this->setText($sheet, "A{$r}", 'No photo requests found for this period.');
            $sheet->mergeCells("A{$r}:{$lastCol}{$r}");
            $sheet->getStyle("A{$r}")->getFont()->setItalic(true);
        }

        foreach ($rows as $i => $info) {
            $requestor = $info->request;

            $values = [
                $i + 1,
                $info->request_id,
                $requestor?->fullname ?? '',
                $requestor?->email ?? '',
                $requestor?->department ?? '',
                $info->category,
                $info->event,
                $info->section,
                $info->venue,
                $info->equipment,
                $info->purpose_document,
                $this->formatDate($info->date_needed),
                $info->start_time,
                $info->end_time,
                $info->approve,
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

        $widths = [6, 16, 24, 30, 22, 18, 30, 18, 26, 28, 36, 16, 14, 14, 20, 24, 22, 16, 12];
        foreach ($widths as $i => $w) {
            $sheet->getColumnDimension($this->col($i + 1))->setWidth($w);
        }

        $sheet->freezePane('A' . ($headerRow + 1));
        $sheet->setAutoFilter("A{$headerRow}:{$lastCol}{$lastRow}");

        $sheet->getPageSetup()->setOrientation('landscape');
        $sheet->getPageSetup()->setFitToWidth(1)->setFitToHeight(0);

        return $spreadsheet;
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
