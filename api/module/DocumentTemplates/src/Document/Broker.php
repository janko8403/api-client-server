<?php


namespace DocumentTemplates\Document;


use Doctrine\Persistence\ObjectManager;
use DocumentTemplates\Entity\DocumentTemplate;
use DocumentTemplates\Output\OutputFactory;

class Broker
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var DataProviderFactory
     */
    private $dataProviderFactory;

    /**
     * @var OutputFactory
     */
    private $outputFactory;

    /**
     * Broker constructor.
     * @param ObjectManager $objectManager
     * @param DataProviderFactory $dataProviderFactory
     * @param OutputFactory $outputFactory
     */
    public function __construct(ObjectManager       $objectManager,
                                DataProviderFactory $dataProviderFactory,
                                OutputFactory       $outputFactory)
    {
        $this->objectManager = $objectManager;
        $this->dataProviderFactory = $dataProviderFactory;
        $this->outputFactory = $outputFactory;
    }

    /**
     * Configures document and triggers generate.
     *
     * @param string $type
     * @param array $params
     * @param array|null $filenameReplace
     * @return array|null
     * @throws \Exception
     */
    public function generate(string $type, array $params, ?array $filenameReplace): ?array
    {
        $template = $this->objectManager->getRepository(DocumentTemplate::class)->findOneBy(['type' => $type]);
        $dataProvider = $this->dataProviderFactory->factory($type);
        $output = $this->outputFactory->factory($type);

        $document = new Document($template, $dataProvider, $output);

        if (isset($filenameReplace['path'])) {
            $document->setDisplayName($filenameReplace['path']);
        } elseif ($filenameReplace) {
            $document->personalizeDisplayName($filenameReplace);
        }

        return $document->generate($params);
    }

    /**
     * @param int $documentTemplateId
     * @param string $type
     * @param array $params
     * @param array|null $filenameReplace
     * @return array|null
     * @throws \Exception
     */
    public function generateByDocumentTemplateId(
        int    $documentTemplateId,
        string $type,
        array  $params,
        ?array $filenameReplace
    ): ?array
    {
        $template = $this->objectManager->find(DocumentTemplate::class, $documentTemplateId);
        $dataProvider = $this->dataProviderFactory->factory($type);
        $output = $this->outputFactory->factory($type);

        $document = new Document($template, $dataProvider, $output);

        if (isset($filenameReplace['path'])) {
            $document->setDisplayName($filenameReplace['path']);
        } elseif ($filenameReplace) {
            $document->personalizeDisplayName($filenameReplace);
        }

        return $document->generate($params);
    }
}