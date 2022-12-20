<?php


namespace DocumentTemplates\Output;


use Hr\Content\PdfService;

class DownloadPdf implements OutputInterface
{
    /**
     * @var PdfService
     */
    private $pdfService;

    /**
     * Inline constructor.
     *
     * @param PdfService $pdfService
     */
    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function generate(array $content, string $displayName, string $type): void
    {
        if (substr($displayName, -4) == '.pdf') {
            $displayName .= '.pdf';
        }

        $this->pdfService->generate(
            $content['body'] ?? '',
            $displayName,
            'D',
            $content['header'] ?? '',
            $content['footer'] ?? ''
        );
    }

    public function getMimeType(): string
    {
        return 'application/pdf';
    }

    public function getResult(): ?array
    {
        return null;
    }
}