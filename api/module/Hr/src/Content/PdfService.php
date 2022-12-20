<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 02.03.2017
 * Time: 15:52
 */

namespace Hr\Content;

use Mpdf\Mpdf;

class PdfService
{
    private $watermark;

    /**
     * @return mixed
     */
    public function getWatermark()
    {
        return $this->watermark;
    }

    /**
     * @param mixed $watermark
     * @return PdfService
     */
    public function setWatermark($watermark)
    {
        $this->watermark = $watermark;

        return $this;
    }

    /**
     * Generates PDF document with given parameters.
     *
     * @param string      $body        PDF body
     * @param string      $filename    Generated PDF's filename
     * @param string      $destination PDF output destination (passed to mPDF Output() method)
     * @param string|null $header      PDF header
     * @param string|null $footer      PDF footer
     * @return string
     * @throws \Mpdf\MpdfException
     */
    public function generate(
        string $body,
        string $filename,
        string $destination = 'D',
        string $header = null,
        string $footer = null
    )
    {
        $pdf = new Mpdf(['tempDir' => getcwd() . '/data/temp']);
        $pdf->shrink_tables_to_fit = 1;

        if (!empty($header)) {
            $pdf->setAutoTopMargin = 'stretch';
            $pdf->SetHTMLHeader($header);
        }

        if (!empty($footer)) {
            $pdf->setAutoBottomMargin = 'stretch';
            $pdf->SetHTMLFooter($footer);
        }

        if (!empty($this->getWatermark())) {
            $pdf->SetWatermarkText($this->getWatermark());
            $pdf->showWatermarkText = true;
            $pdf->watermarkImgBehind = true;
        }

        @$pdf->WriteHTML($body);

        return $pdf->Output($filename, $destination);
    }
}