<?php


namespace DocumentTemplates\Document;


use DocumentTemplates\Entity\DocumentTemplate;
use DocumentTemplates\Output\OutputInterface;

class Document
{
    /**
     * @var DocumentTemplate
     */
    private $template;

    /**
     * @var DataProvider
     */
    private $dataProvider;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var string
     */
    private $displayName;

    /**
     * Document constructor.
     * @param DocumentTemplate $template
     * @param DataProvider $dataProvider
     * @param OutputInterface $output
     */
    public function __construct(DocumentTemplate $template, DataProvider $dataProvider, OutputInterface $output)
    {
        $this->template = $template;
        $this->dataProvider = $dataProvider;
        $this->output = $output;
        $this->displayName = $template->getName();
    }

    /**
     * Generates document.
     *
     * @param array $params
     * @return array|null
     */
    public function generate(array $params): ?array
    {
        $parts = [
            'header' => $this->dataProvider->replace($this->template->getContentHeader(), $params),
            'body' => $this->dataProvider->replace($this->template->getContentBody(), $params),
            'footer' => $this->dataProvider->replace($this->template->getContentFooter(), $params),
        ];

        $this->output->generate($parts, $this->displayName, $this->output->getMimeType());

        return $this->output->getResult();
    }

    /**
     * Personalizes document display name.
     *
     * @param array $data
     */
    public function personalizeDisplayName(array $data): void
    {
        $this->displayName = sprintf($this->displayName, ...$data);
    }

    /**
     * Sets document display name.
     *
     * @param string $name
     */
    public function setDisplayName(string $name): void
    {
        $this->displayName = $name;
    }
}