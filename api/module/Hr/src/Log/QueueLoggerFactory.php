<?php

namespace Hr\Log;

use Laminas\Log\Formatter\Simple;
use Laminas\Log\Logger;
use Laminas\Log\Processor\Backtrace;
use Laminas\Log\PsrLoggerAdapter;
use Laminas\Log\Writer\Stream;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class QueueLoggerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $dir = $this->getDir();
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $filename = $dir . DIRECTORY_SEPARATOR . date('H') . 'h.log';
        $writer = new Stream($filename);
        $writer->setFormatter(new Simple('%timestamp% %priorityName% (%priority%): %message% %extra%', 'c'));

        $writerOutput = new Stream('php://output');
        $writerOutput->setFormatter(new Simple('%timestamp% %priorityName% (%priority%): %message%', 'c'));

        $logger = new Logger();
        $logger->addProcessor(new Backtrace());
        $logger->addWriter($writer);
        $logger->addWriter($writerOutput);

        return new PsrLoggerAdapter($logger);
    }

    private function getDir(): string
    {
        return implode(DIRECTORY_SEPARATOR, [getcwd(), 'data', 'rabbitmq', date('Y'), date('m'), date('d')]);
    }
}