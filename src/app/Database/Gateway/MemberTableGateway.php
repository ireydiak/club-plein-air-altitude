<?php


namespace App\Database\Gateway;


use App\Database\PDOBinding;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Driver\Statement;

class MemberTableGateway extends BaseGateway {
    /**
     * @var Statement
     */
    private $createStmt;

    /**
     * @var Statement
     */
    private $createEmailStmt;

    /**
     * @var Statement
     */
    private $createUniversityStmt;

    /**
     * @var Statement
     */
    private $createFacebookStmt;

    /**
     * @var Statement
     */
    private $deleteStatement;

    /**
     * @var Statement
     */
    private $findStatement;

    /**
     * @var Statement
     */
    private $permanentStmt;

    /**
     * @var Statement
     */
    private $adminStmt;

    /**
     * @var Statement
     */
    private $findPermanentStmt;

    /**
     * @var Statement
     */
    private $findAdminStmt;

    /**
     * @var Statement
     */
    private $updateStmt;

    /**
     * Serializes a new member entity in the database
     *
     * @param string $firstName @required
     * @param string $lastName @required
     * @param string $password @required
     * @param bool $isPermanent
     * @param bool $isAdmin
     * @param string|null $email
     * @param string|null $facebook
     * @param string|null $university
     * @return array
     * @throws DBALException
     */
    public function create(string $firstName, string $lastName, string $password, bool $isPermanent = false,
                           bool $isAdmin = false, string $email = null, string $facebook = null, string $university = null): array {
        $query = "CALL create_member(
                    @member_first_name   := :first_name,
                    @member_last_name    := :last_name,
                    @member_email        := :email,
                    @member_password     := :password,
                    @member_cip		   := :university,
                    @member_facebook     := :facebook,
                    @is_permanent 	   := :is_permanent,
                    @is_admin          := :is_admin
                )";

        $bindings = array(
            new PDOBinding('first_name', $firstName, ParameterType::STRING),
            new PDOBinding('last_name', $lastName, ParameterType::STRING),
            new PDOBinding('email', $email, ParameterType::STRING),
            new PDOBinding('password', $password, ParameterType::STRING),
            new PDOBinding('facebook', $facebook, ParameterType::STRING),
            new PDOBinding('university', $university, ParameterType::STRING),
            new PDOBinding('is_permanent', $isPermanent, ParameterType::BOOLEAN),
            new PDOBinding('is_admin', $isAdmin, ParameterType::BOOLEAN),
        );

        $this->prepareStatement($this->createStmt, $query, $bindings);
        $this->createStmt->execute();

        $results = $this->createStmt->fetchAll();
        $this->createStmt->closeCursor();

        return isset($results[0]) ? $results[0] : array();
    }

    /**
     * @param int $memberId
     * @param string $firstName
     * @param string $lastName
     * @param string $password
     * @param bool $isPermanent
     * @param bool $isAdmin
     * @param string|null $email
     * @param string|null $facebook
     * @param string|null $university
     * @return array
     * @throws DBALException
     */
    public function update(int $memberId, string $firstName, string $lastName, string $password, bool $isPermanent = false,
                           bool $isAdmin = false, string $email = null, string $facebook = null, string $university = null): array {

        $statement = "CALL update_member(
                @member_uid		     := :member_id,
				@member_first_name   := :first_name,
				@member_last_name    := :last_name,
				@member_email        := :email,
				@member_password     := :password,
				@member_cip	     := :cip,
				@member_facebook     := :facebook,
				@is_permanent 	     := :is_permanent,
				@is_admin            := :is_admin
			)";

        $bindings = array(
            new PDOBinding('member_id', $memberId, ParameterType::INTEGER),
            new PDOBinding('first_name', $firstName, ParameterType::STRING),
            new PDOBinding('last_name', $lastName, ParameterType::STRING),
            new PDOBinding('email', $email, ParameterType::STRING),
            new PDOBinding('password', $password, ParameterType::STRING),
            new PDOBinding('facebook', $facebook, ParameterType::STRING),
            new PDOBinding('cip', $university, ParameterType::STRING),
            new PDOBinding('is_permanent', $isPermanent, ParameterType::BOOLEAN),
            new PDOBinding('is_admin', $isAdmin, ParameterType::BOOLEAN),

        );

        $this->prepareStatement($this->updateStmt, $statement, $bindings);

        $this->updateStmt->execute();
        $results = $this->updateStmt->fetchAll();
        $this->updateStmt->closeCursor();

        return isset($results[0]) ? $results[0] : array();

    }

    /**
     * Returns a resource found by its primary key
     *
     * @param int $memberId
     * @return array
     * @throws DBALException
     */
    public function find(int $memberId): array {

        $this->prepareStatement(
            $this->findStatement,
            "SELECT * FROM member_view WHERE member_id = :member_id",
            array(new PDOBinding('member_id', $memberId, ParameterType::INTEGER))
        );
        $this->findStatement->execute();

        $results = $this->findStatement->fetchAll();

        return isset($results[0]) ? $results[0] : $results;
    }

    public function findAll(): array {
        $stmt = $this->conn->query("SELECT * FROM member_view");
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();

        return $rows;
    }

    /**
     * Destroys a member from the database
     *
     * @param int $memberId
     * @return int The number of affected rows
     * @throws DBALException
     */
    public function delete(int $memberId): int {
        $this->prepareStatement(
            $this->deleteStatement,
            "DELETE FROM member WHERE member_id = :member_id",
            array(new PDOBinding('member_id', $memberId, ParameterType::INTEGER))
        );

        return $this->deleteStatement->execute();
    }

    /**
     * Returns the number of rows in the table
     *
     * @return int
     * @throws DBALException
     */
    public function countRows() {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM member");
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();

        return isset($rows[0]) ? (int)$rows[0]['total'] : 0;
    }
}
