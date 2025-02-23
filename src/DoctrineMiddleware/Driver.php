<?php declare(strict_types=1);

namespace OM\Doctrine\QueriesLogger\DoctrineMiddlewares;

use Doctrine\DBAL\Driver as DriverInterface;
use Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;
use OM\Doctrine\QueriesLogger\ConnectionPanel;

final class Driver extends AbstractDriverMiddleware
{

    public function __construct(DriverInterface $driver, private ConnectionPanel $logger)
    {
        parent::__construct($driver);
    }

    public function connect(array $params): Connection
    {
        return new Connection(parent::connect($params), $this->logger);
    }

}
