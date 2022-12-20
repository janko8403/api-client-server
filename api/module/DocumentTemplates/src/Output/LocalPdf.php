<?php


namespace DocumentTemplates\Output;


use Hr\Content\PdfService;

class LocalPdf implements OutputInterface
{
    /**
     * @var PdfService
     */
    private $service;

    /**
     * @var string
     */
    private $displayName;

    /**
     * LocalFile constructor.
     *
     * @param PdfService $service
     */
    public function __construct(PdfService $service)
    {
        $this->service = $service;
    }

    public function generate(array $content, string $displayName, string $type): void
    {
        $this->displayName = $displayName;

        if (!file_exists(dirname($displayName))) {
            mkdir(dirname($displayName), 0755, true);
        }

        $this->service->generate(
            $content['body'] ?? '',
            $displayName,
            'F',
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
        return [
            'path' => $this->displayName,
            'filename' => basename($this->displayName),
        ];
    }
}