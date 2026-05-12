<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfExportService
{
    public function export($data, $fileName, $headers, $callbackData
    ) {
        $rows = [];
        foreach ($data as $item) {
            $rows[] = $callbackData($item);
        }
        // $pdf = Pdf::loadView('exports.common-pdf', ['headers' => $headers, 'rows' => $rows]);
        $pdf = Pdf::loadView('exports.project-manipulation-pdf',
            [
                'plots' => $data,
            ]
        );
        return $pdf->download($fileName.'.pdf');
    }
}
