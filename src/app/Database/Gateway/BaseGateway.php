<?php

namespace App\Database\Gateway;

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
     * BaseGateway constructor.
     * @param Connection $conn
     */
    public function __construct(Connection $conn) {
        $this->conn = $conn;
        $this->conn->setAutoCommit(false); // disable auto-commit
    }

    /**
     * Returns a standardized PDO Statement for a gateway
     *
     * @param string $query
     * @return bool|Statement
     * @throws DBALException
     */
    protected function prepareStatement(string $query) {
        return $this->conn->prepare($query);
    }

}
