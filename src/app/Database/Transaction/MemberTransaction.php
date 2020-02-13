<?php


namespace App\Database\Transaction;


use App\Database\Gateway\MemberTableGateway;
use App\Domain\Member;
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

    public function all()
    {
        return collect($this->mtg->findAll())->map(function($el) { return new Member($el); });
    }

    public function findById(int $id): ?Member {
        if (!empty($this->mtg->find($id))) {
             return new Member($this->mtg->find($id));
        }

        return NULL;
    }

    public function update(int $id, array $attributes): ?Member {

        $result = $this->mtg->update(
            $id,
            $attributes['firstName'],
            $attributes['lastName'],
            $attributes['password'],
            $attributes['isPermanent'],
            $attributes['isAdmin'],
            $attributes['email'],
            $attributes['facebookLink'],
            $attributes['cip']
        );

        if (!empty($result)) {
            return new Member($result);
        }

        return NULL;
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
                $attributes['facebookLink'],
                $attributes['cip'],
                $attributes['phone']
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

    public function destroy($id): bool {
        $this->conn->beginTransaction();

        $result = $this->mtg->delete($id);

        $this->conn->commit();

        return $result;
    }
}
