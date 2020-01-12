<?php


namespace Tests\Unit\Domain;

use App\Domain\Member;
use Tests\TestCase;

/**
 * @method assertTrue(bool $equals)
 * @method assertFalse(bool $equals)
 */
class MemberTest extends TestCase
{
    /**
     * @var array
     */
    protected $thatcher = array(
        'first_name' => 'Margareth',
        'last_name'  => 'Thatcher',
        'password'   => 'secret'
    );

    /**
     * @var array
     */
    protected $churchill = array(
        'first_name' => 'Winston',
        'last_name' => 'Churchill',
        'password' => 'secret'
    );

    /**
     * @var array
     */
    protected $invalidAttributes = array(
        'first_name' => '',
        'last_name' => 'Churchill',
        'password' => 'secret'
    );


    public function testEquals() {
        $member = new Member($this->thatcher);
        $same = new Member($this->thatcher);
        $different = new Member($this->churchill);

        $this->assertTrue($member->equals($same));
        $this->assertFalse($member->equals($different));
    }

    public function testValidate() {
        $member = new Member($this->thatcher);
        $this->assertFalse($member->validate());
        $member->email = 'mthatcher@email.com';
        $this->assertTrue($member->validate());

        $invalid = new Member($this->invalidAttributes);
        $this->assertFalse($invalid->validate());
    }

}
