<?php


namespace Tests\Unit\Transaction;


use App\Database\Adapter\AdapterException;
use App\Database\Adapter\MemberAdapter;
use App\Database\Transaction\MemberTransaction;
use App\Domain\Member;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Tests\TestCase;


/**
 * @method assertTrue($condition)
 */
class MemberTransactionTest extends TestCase  {

    /**
     * @var MemberTransaction
     */
    protected $transaction;

    /**
     * @var Connection
     */
    protected $conn;

    /**
     * @var array
     */
    protected $thatcher = array(
        'first_name' => 'Margareth',
        'last_name' => 'Thatcher',
        'password' => 'secret',
        'email' => 'mthatcher@gmail.com',
        'facebook_link' => 'facebook.com/mthatcher',
        'university_id' => 'mtha2009'
    );

    protected $churchill = array(
        'first_name' => 'Winston',
        'last_name' => 'Churchill',
        'password' => 'unsage',
        'email' => 'winchurch@gmail.com',
        'facebook_link' => 'facebook.com/winchurch',
        'university_id' => 'wchu2009'
    );

    /**
     * @var Member
     */
    protected $memberThatcher;

    /**
     * @var Member
     */
    protected $memberChurchill;

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
            $this->transaction = new MemberTransaction($this->conn);
            $this->memberThatcher = new Member($this->thatcher);
            $this->memberChurchill = new Member($this->churchill);
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
     * @throws AdapterException
     */
    public function testCreate() {
       $row = $this->transaction->create(MemberAdapter::toPDOStatement($this->memberThatcher));
       $persistedMember = MemberAdapter::toDomain($row);
       $this->assertTrue($persistedMember->equals($this->memberThatcher));
    }


}
