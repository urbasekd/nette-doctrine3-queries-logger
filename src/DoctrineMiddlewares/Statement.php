<?php declare(strict_types=1);

namespace OM\Doctrine\QueriesLogger\DoctrineMiddlewares;

use Doctrine\DBAL\Driver\Middleware\AbstractStatementMiddleware;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement as StatementInterface;
use Doctrine\DBAL\ParameterType;
use OM\Doctrine\QueriesLogger\ConnectionPanel;

final class Statement extends AbstractStatementMiddleware
{

    private array $params = [];

    public function __construct(StatementInterface $statement, private string $sql, private ConnectionPanel $logger)
    {
        parent::__construct($statement);
    }

    public function bindValue(int|string $param, mixed $value, ParameterType $type): void
    {
        $this->params[] = $value;
        parent::bindValue($param, $value, $type);
    }

    public function execute(): Result
    {
        $this->logger->startQuery($this->sql, $this->params);
        $result = parent::execute();
        $this->logger->stopQuery();

        return $result;
    }

}
