<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExportService
{
    public function export($data, $fileName, $headers, $callbackData)
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column.'1', $header);
            $column++;
        }
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I'.(count($data) + 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $row = 2;
        foreach ($data as $item) {
            $values = $callbackData($item);
            $column = 'A';
            foreach ($values as $value) {
                $sheet->setCellValue($column.$row, $value);
                $column++;
            }
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        $path = storage_path("app/{$fileName}.xlsx");
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
