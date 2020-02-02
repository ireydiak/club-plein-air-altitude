<?php

namespace App\Database\Gateway;

use App\Database\PDOBinding;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Connection;

/**
 * Class BaseGateway
 *
 * A gateway is meant as an atomic operation on a database.
 * Every method should only affect a single table.
 * Operations on multiple tables for complex relationships are handled within Transactions using
 * $pdo->beginTransaction.
 * This makes database operations easy to rollback while keeping a minimal separation of concerns.
 *
 * @package App\Database\Gateway
 */
class BaseGateway {

    /**
     * @var Connection;
     */
    protected $conn;

    /**
     * BaseGateway constructor
     *
     * @param Connection $conn
     */
    public function __construct(Connection $conn) {
        $this->conn = $conn;
    }

    /**
     * Prepares a standardized PDO Statement for a gateway with its bindings
     *
     * @param $stmt
     * @param string $statement
     * @param array<PDOBinding> $bindings
     * @throws DBALException
     */
    protected function prepareStatement(&$stmt, string $statement, array $bindings): void {
        if ($stmt == null) {
            $stmt = $this->conn->prepare($statement);
        }

        foreach ($bindings as $b) {
            $stmt->bindValue($b->param, $b->value, $b->type);
        }
    }

}
