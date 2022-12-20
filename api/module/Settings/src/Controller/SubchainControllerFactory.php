<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 19.09.17
 * Time: 03:52
 */

namespace Settings\Controller;


use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Settings\Form\SubchainForm;
use Settings\Form\SubchainSearchForm;
use Settings\Table\SubchainTable;
use Laminas\I18n\Translator\Translator;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;

class SubchainControllerFactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SubchainController(
            $container->get(ObjectManager::class),
            $container->get(SubchainTable::class),
            $container->get(SubchainForm::class),
            $container->get(Translator::class),
            $container->get(SubchainSearchForm::class)
        );
    }
}