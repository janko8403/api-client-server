<?php

namespace DocumentTemplates\Service;

use Doctrine\Persistence\ObjectManager;
use Hr\Authentication\AuthenticationService;
use Hr\Content\PdfService;
use Hr\File\FileService;
use Interop\Container\ContainerInterface;
use Laminas\ApiTools\MvcAuth\Identity\GuestIdentity;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Factory class for DocimentService
 *
 * @package DocumentTemplates\Service
 */
class DocumentServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return DocumentService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userId = $container->get(AuthenticationService::class)->getIdentity()['id'] ?? null;
        if (!$userId) {
            $identity = $container->get('application')->getMvcEvent()->getParam('Laminas\ApiTools\MvcAuth\Identity');
            $userId = ($identity instanceof GuestIdentity) ? -1 : $identity->getUser()->getId();
        }

        return new DocumentService(
            $container->get(ObjectManager::class),
            $container->get(PdfService::class),
            $container->get(FileService::class),
            $userId
        );
    }
}