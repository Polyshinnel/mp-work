<?php

namespace App\Service;

class PrintLabelPdfService
{
    private const PAGE_WIDTH_MM = 70;
    private const PAGE_HEIGHT_MM = 50;

    public function generate(string $type, string $data, ?string $sign, int $quantity): string
    {
        $pdf = new \TCPDF('L', 'mm', [self::PAGE_WIDTH_MM, self::PAGE_HEIGHT_MM], true, 'UTF-8', false);
        $pdf->SetCreator(config('app.name'));
        $pdf->SetAuthor(config('app.name'));
        $pdf->SetTitle('Generated print label');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(2, 2, 2);
        $pdf->SetAutoPageBreak(false);

        for ($page = 0; $page < $quantity; $page++) {
            $pdf->AddPage();
            $this->drawLabel($pdf, $type, $data, $sign ?? '');
        }

        return $pdf->Output('', 'S');
    }

    private function drawLabel(\TCPDF $pdf, string $type, string $data, string $sign): void
    {
        if ($type === 'qr-code') {
            $size = 30;
            $x = (self::PAGE_WIDTH_MM - $size) / 2;
            $signHeight = 12;
            $gap = $sign === '' ? 0 : 2;
            $groupHeight = $size + $gap + ($sign === '' ? 0 : $signHeight);
            $y = (self::PAGE_HEIGHT_MM - $groupHeight) / 2;
            $pdf->write2DBarcode($data, 'QRCODE,M', $x, $y, $size, $size, ['border' => false], 'N');
            $signY = $y + $size + $gap;
        } else {
            $width = 64;
            $height = 17;
            $x = (self::PAGE_WIDTH_MM - $width) / 2;
            $signHeight = 12;
            $gap = $sign === '' ? 0 : 2;
            $groupHeight = $height + $gap + ($sign === '' ? 0 : $signHeight);
            $y = (self::PAGE_HEIGHT_MM - $groupHeight) / 2;
            $style = [
                'position' => 'C',
                'align' => 'C',
                'stretch' => true,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => false,
                'hpadding' => 0,
                'vpadding' => 0,
                'text' => false,
            ];
            $pdf->write1DBarcode($data, 'C128', $x, $y, $width, $height, 0.4, $style, 'N');
            $signY = $y + $height + $gap;
        }

        if ($sign !== '') {
            $pdf->SetFont('freesans', '', 9);
            $pdf->SetXY(3, $signY);
            $pdf->MultiCell(self::PAGE_WIDTH_MM - 6, $signHeight, $sign, 0, 'C', false, 1, '', '', true, 0, false, true, $signHeight, 'M');
        }
    }
}
