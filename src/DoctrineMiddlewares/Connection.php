<?php declare(strict_types=1);

namespace OM\Doctrine\QueriesLogger\DoctrineMiddlewares;

use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Doctrine\DBAL\Driver\Middleware\AbstractConnectionMiddleware;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement as StatementInterface;
use OM\Doctrine\QueriesLogger\ConnectionPanel;

class Connection extends AbstractConnectionMiddleware
{

    public function __construct(ConnectionInterface $connection, private ConnectionPanel $logger)
    {
        parent::__construct($connection);
    }

    public function prepare(string $sql): StatementInterface
    {
        return new Statement(parent::prepare($sql), $sql, $this->logger);
    }

    public function query(string $sql): Result
    {
        $this->logger->startQuery($sql);
        $result = parent::query($sql);
        $this->logger->stopQuery();

        return $result;
    }

}
