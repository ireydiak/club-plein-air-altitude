<?php


namespace App\Database\Transaction;


use App\Database\Gateway\MemberTableGateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;

class MemberTransaction
{
    /**
     * @var Connection
     */
    private $conn;

    /**
     * @var MemberTableGateway
     */
    private $mtg;

    public function __construct(Connection $conn) {
        $this->conn = $conn;
        $this->mtg = new MemberTableGateway($this->conn);
    }

    /**
     * @param array $attributes
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function create(array $attributes): array {
        try {
            $this->conn->beginTransaction();

            $row = $this->mtg->create(
                $attributes['firstName'],
                $attributes['lastName'],
                $attributes['password'],
                $attributes['isPermanent'],
                $attributes['isAdmin'],
                $attributes['email'],
                $attributes['facebook'],
                $attributes['cip']
            );

            $this->conn->commit();

            return $row;
        } catch (ConnectionException $e) {
            try {
                $this->conn->rollBack();
                return array();
            } catch (ConnectionException $e) {
                die($e->getMessage());
            }
        }
    }
}
