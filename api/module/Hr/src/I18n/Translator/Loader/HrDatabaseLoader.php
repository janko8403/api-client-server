<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 08.11.2016
 * Time: 13:41
 */

namespace Hr\I18n\Translator\Loader;


use Doctrine\Persistence\ObjectManager;
use Hr\Entity\Translation;
use Laminas\I18n\Translator\Loader\RemoteLoaderInterface;
use Laminas\I18n\Translator\TextDomain;

class HrDatabaseLoader implements RemoteLoaderInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * HrDatabaseLoader constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function load($locale, $textDomain)
    {
        $translations = $this->objectManager->getRepository(Translation::class)->findAll();

        $textDomain = new TextDomain();
        foreach ($translations as $translation) {
            $textDomain[$translation->getKey()] = $translation->getValue();
        }

        return $textDomain;
    }

}