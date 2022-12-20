<?php


namespace Hr\Doctrine\Cache;


use Doctrine\Common\Cache\FilesystemCache;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class FilesystemFactory implements FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dir = $container->get('config')['doctrine']['cache']['filesystem']['directory'];

        return new FilesystemCache($dir);
    }
}