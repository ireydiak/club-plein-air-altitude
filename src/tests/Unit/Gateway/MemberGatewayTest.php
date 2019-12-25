<?php

namespace Tests\Unit\Gateway;

use App\Domain\Member;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Tests\TestCase;
use App\Database\Gateway\MemberTableGateway;

/**
 * @method assertEquals($expected, $actual)
 */
class MemberGatewayTest extends TestCase {
    /**
     * @var Connection
     */
    protected $conn;

    /**
     * @var MemberTableGateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $thatcher = array(
        'first_name'    => 'Margareth',
        'last_name'     => 'Thatcher',
        'password'      => 'secret',
        'email'         => 'mthatcher@gmail.com',
        'facebook_link' => 'facebook.com/mthatcher',
        'cip'           => 'mtha2009'
    );

    protected $churchill = array(
        'first_name'    => 'Winston',
        'last_name'     => 'Churchill',
        'password'      => 'unsage',
        'email'         => 'winchurch@gmail.com',
        'facebook_link' => 'facebook.com/winchurch',
        'cip'           => 'wchu2009'
    );

    /**
     *
     */
    public function setUp(): void {
        $config = new Configuration();

        $connectionParams = array(
            'dbname' => 'club-plein-air-altitude',
            'user' => 'admin',
            'password' => 'secret',
            'host' => 'mysql',
            'driver' => 'pdo_mysql',
        );
        try {
            $this->conn = DriverManager::getConnection($connectionParams, $config);
            $this->gateway = new MemberTableGateway($this->conn);
        } catch (DBALException $e) {
            die($e->getMessage());
        }
    }

    /**
     * @throws ConnectionException
     */
    public function tearDown(): void {
        $this->conn->rollBack();
    }

    /**
     * @throws DBALException
     */
    public function testCreate() {
        $row = $this->gateway->create(
            $this->thatcher['first_name'],
            $this->thatcher['last_name'],
            $this->thatcher['password'],
            true,
            true,
            $this->thatcher['email'],
            $this->thatcher['facebook_link'],
            $this->thatcher['cip'],
        );

        foreach ($this->thatcher as $key => $value) {
            $this->assertEquals($value, $row[$key]);
        }
    }

    /**
     * @throws DBALException
     */
    public function testUpdate() {
        $row = $this->createMember();

        $row = $this->gateway->update(
            (int) $row['member_id'],
            $this->churchill['first_name'],
            $this->churchill['last_name'],
            $this->churchill['password'],
            false,
            false,
            $this->churchill['email'],
            $this->churchill['facebook_link'],
            $this->churchill['cip']
        );

        // Winston Churchill IS Winston Churchill
        foreach ($this->churchill as $key => $value) {
            $this->assertEquals($value, $row[$key]);
        }
    }

    /**
     * @throws ConnectionException
     * @throws DBALException
     */
    public function testGatewayPersists() {
        $this->conn->beginTransaction();
        $row = $this->gateway->create('Margareth', 'Thatcher', 'secret');
        $this->conn->commit();

        $this->assertEquals(1, $this->gateway->countRows());

        $this->conn->beginTransaction();
        $this->gateway->delete($row['member_id']);
        $this->conn->commit();

        $this->assertEquals(0, $this->gateway->countRows());
    }

    /**
     * @throws DBALException
     */
    public function testFinalDataWasNotPersisted() {
        $this->assertEquals(0, $this->gateway->countRows());
    }

    /**
     * @return array
     * @throws DBALException
     */
    private function createMember() {
        return $this->gateway->create('Margareth', 'Thatcher', 'secret');
    }
}
