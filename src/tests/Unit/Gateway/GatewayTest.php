<?php

namespace Tests\Unit\Gateway;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Tests\TestCase;
use App\Database\Gateway\MemberTableGateway;

class GatewayTest extends TestCase
{
    /**
     * @var Connection
     */
    protected $conn;

    /**
     * @var MemberTableGateway
     */
    protected $gateway;

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

    public function testGateway() {
        $this->gateway->create('Margareth', 'Thatcher', 'secret');

        $this->assertEquals(1, $this->gateway->countRows());
    }

    public function testGatewayPersists() {
        $this->conn->beginTransaction();
        $uid = $this->gateway->create('Margareth', 'Thatcher', 'secret');
        $this->conn->commit();

        $this->assertEquals(1, $this->gateway->countRows());

        $this->conn->beginTransaction();
        $this->gateway->delete($uid);
        $this->conn->commit();

        $this->assertEquals(0, $this->gateway->countRows());

    }

    public function testFinalDataWasNotPersisted() {
        $this->assertEquals(0, $this->gateway->countRows());
    }
}
