<?php


namespace DocumentTemplates\Output;


interface OutputInterface
{
    /**
     * Generates output document.
     *
     * @param array $content
     * @param string $displayName
     * @param string $type
     */
    public function generate(array $content, string $displayName, string $type): void;

    /**
     * Gets file MIME type.
     *
     * @return string
     */
    public function getMimeType(): string;

    /**
     * Gets document data (filename etc.)
     *
     * @return array|null
     */
    public function getResult(): ?array;
}