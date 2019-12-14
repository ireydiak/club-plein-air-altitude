<?php


namespace App\Database\Gateway;


use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Driver\Statement;

class MemberTableGateway extends BaseGateway
{

    /**
     * @var Statement
     */
    private $createStatement;

    /**
     * @var Statement
     */
    private $createEmailStatement;

    /**
     * @var Statement
     */
    private $createUniversityStatement;

    /**
     * @var Statement
     */
    private $createFacebookStatement;

    /**
     * @var Statement
     */
    private $deleteStatement;

    /**
     * Serializes a new member entity in the database
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $passwd
     * @return int The last inserted id
     * @throws DBALException
     */
    public function create(string $firstName, string $lastName, string $passwd): int {
        if ($this->createStatement == null) {
            $this->createStatement = $this->prepareStatement(
                "INSERT INTO member (first_name, last_name, password, created_at, updated_at) VALUES (:first_name, :last_name, :password, NOW(), NOW())"
            );
        }

        $this->createStatement->bindValue('first_name', $firstName, ParameterType::STRING);
        $this->createStatement->bindValue('last_name', $lastName, ParameterType::STRING);
        $this->createStatement->bindValue('password', $passwd, ParameterType::STRING);

        $this->createStatement->execute();

        return $this->conn->lastInsertId();
    }

    /**
     * Links a member with an email address
     *
     * @param int $uid
     * @param string $email
     * @return int The number of affected rows, should be 0 or 1
     * @throws DBALException
     */
    public function createEmail(int $uid, string $email): int {
        if ($this->createEmailStatement == null) {
            $this->createStatement = $this->prepareStatement(
                "INSERT INTO member_email(member_id, email) VALUE (:member_id, :email)"
            );
        }

        $this->createEmailStatement->bindValue('member_id', $uid);
        $this->createEmailStatement->bindValue('email', $email);

        return $this->createEmailStatement->execute();
    }

    /**
     * Links a member with a university unique identifier
     *
     * @param int $uid
     * @param string $university
     * @return int The number of affected rows, should be 0 or 1
     * @throws DBALException
     */
    public function createUniversity(int $uid, string $university): int {
        if ($this->createUniversityStatement == null) {
            $this->createUniversityStatement = $this->prepareStatement(
                "INSERT INTO member_university(member_id, cip) VALUE (:member_id, :university)"
            );
        }

        $this->createUniversityStatement->bindValue('member_id', $uid);
        $this->createUniversityStatement->bindValue('university', $university);

        return $this->createUniversityStatement->execute(
            array(':member_id' => $uid, ':university' => $university)
        );
    }

    /**
     * Links a member with a facebook account
     *
     * @param int $uid
     * @param string $facebook
     * @return int The number of affected rows, should be 0 or 1
     * @throws DBALException
     */
    public function createFacebook(int $uid, string $facebook): int {
        if ($this->createFacebookStatement == null) {
            $this->createFacebookStatement = $this->prepareStatement(
                "INSERT INTO member_facebook(member_id, facebook_link) VALUE (:member_id, :facebook_link)"
            );
        }

        $this->createFacebookStatement->bindValue('member_id', $uid);
        $this->createFacebookStatement->bindValue('facebook_link', $facebook);

        return $this->createFacebookStatement->execute();
    }

    /**
     * Destroys a member from the database
     *
     * @param int $uid
     * @return int The number of affected rows
     * @throws DBALException
     */
    public function delete(int $uid): int {
        if ($this->deleteStatement == null) {
            $this->deleteStatement = $this->prepareStatement(
                "DELETE FROM member WHERE member_id = :member_id"
            );
        }

        $this->deleteStatement->bindValue('member_id', $uid);

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

        if (isset($rows[0])) {
            return (int) $rows[0]['total'];
        }

        return 0;
    }
}
