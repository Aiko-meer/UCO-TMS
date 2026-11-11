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
 * Exports the Social Media archive (posted requests) to an Excel file.
 *
 *   GET /socmed/archive/export?month=11&year=2026
 *
 * "month" and "year" are both optional and filter on the date the request was
 * created, the same way the archive table filters do. The file name follows the
 * filter, e.g. Socmed_Archive_November_2026.xlsx
 */
class SocmedArchiveExportController extends Controller
{
    private const POSTED = 2;

    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'month' => ['nullable', 'regex:/^(0?[1-9]|1[0-2])$/'], // 1-12, "01" is fine
            'year'  => ['nullable', 'digits:4'],
        ]);

        $month = isset($data['month']) ? (int) $data['month'] : null;
        $year  = isset($data['year'])  ? (int) $data['year']  : null;

        $rows = Requestor::with('socmed.user')
            ->whereHas('socmed', function ($q) use ($month, $year) {
                $q->where('status', self::POSTED);

                if ($year) {
                    $q->whereYear('created_at', $year);
                }
                if ($month) {
                    $q->whereMonth('created_at', $month);
                }
            })
            ->get()
            ->sortBy(fn ($r) => $r->socmed->created_at)
            ->values();

        $monthName = $month ? Carbon::create(2000, $month, 1)->format('F') : null;
        $period    = $this->periodLabel($monthName, $year);

        $spreadsheet = $this->buildSpreadsheet($rows, $period);

        $fileName = 'Socmed_Archive_' . $this->fileSuffix($monthName, $year) . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /* ---------------------------------------------------------------------
     | Spreadsheet
     | ------------------------------------------------------------------- */

    private function buildSpreadsheet($rows, string $period): Spreadsheet
    {
        $headers = [
            'No.', 'Request ID', 'Requestor', 'Email', 'Department',
            'Purpose', 'Category', 'Section', 'Specification', 'Content Information',
            'Approved By', 'Produced By',
            'Date Requested', 'Date Needed', 'Date Published', 'Status',
        ];
        $lastCol = $this->col(count($headers));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Social Media Archive');

        // ----- Title block -----
        $this->setText($sheet, 'A1', 'Social Media Requests - Posted Archive');
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
            $this->setText($sheet, "A{$r}", 'No posted requests found for this period.');
            $sheet->mergeCells("A{$r}:{$lastCol}{$r}");
            $sheet->getStyle("A{$r}")->getFont()->setItalic(true);
        }

        foreach ($rows as $i => $req) {
            $info = $req->socmed;

            $values = [
                $i + 1,
                $req->request_id,
                $req->fullname,
                $req->email,
                $req->department,
                $info->purpose,
                $info->category,
                $info->section,
                $info->specification,
                $info->content_information,
                $info->approve,
                $info->user?->fullname ?? $info->produce,
                $info->created_at?->format('M d, Y h:i A'),
                $this->formatDate($info->date_needed),
                $this->formatDate($info->published),
                'Posted',
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

        $widths = [6, 16, 24, 30, 22, 36, 18, 18, 28, 40, 20, 22, 22, 16, 16, 12];
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
