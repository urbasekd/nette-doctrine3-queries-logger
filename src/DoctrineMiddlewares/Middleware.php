<?php declare(strict_types=1);

namespace OM\Doctrine\QueriesLogger\DoctrineMiddlewares;

use Doctrine\DBAL\Driver as DriverInterface;
use Doctrine\DBAL\Driver\Middleware as MiddlewareInterface;
use OM\Doctrine\QueriesLogger\ConnectionPanel;

final class Middleware implements MiddlewareInterface
{

    public function __construct(private ConnectionPanel $logger)
    {
    }

    public function wrap(DriverInterface $driver): DriverInterface
    {
        return new Driver($driver, $this->logger);
    }

}
