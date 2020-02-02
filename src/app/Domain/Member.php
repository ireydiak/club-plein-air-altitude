<?php

namespace App\Domain;

class Member extends BaseDomain
{
    /**
     * @var int
     */
    protected $memberId;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    protected $password;

    /**
     * @nullable
     * @var string
     */
    public $universityId;

    /**
     * @nullable
     * @var string
     */
    public $facebookLink;

    /**
     * @nullable
     * @var string
     */
    public $email;

    /**
     * @var int
     */
    public const REGULAR_ROLE = 0;

    /**
     * @var int
     */
    public const PERMANENT_ROLE = 1;

    /**
     * @var int
     */
    public const ADMIN_ROLE = 2;

    /**
     * @var int
     */
    protected $role;

    public function __construct(array $attributes) {
        $this->fill($attributes);
        $this->role = $this->role ?? self::REGULAR_ROLE;
    }

    public function setPassword(string $password): Member {
        // todo call hash
        return $this;
    }

    public function getKey(): int {
        return $this->memberId;
    }

    public function getPassword(): string {
        return $this->password;
    }

    /**
     * @Override
     * @param BaseDomain $other
     * @return bool
     */
    public function equals(BaseDomain $other): bool {
        if (!$other instanceof Member) {
            return false;
        }
        return $this->firstName    == $other->firstName &&
            $this->lastName        == $other->lastName &&
            $this->email            == $other->email &&
            $this->facebookLink    == $other->facebookLink &&
            $this->role             == $other->role;
    }

    public function setRole(int $role) {
        $this->role = $role;
    }

    public function isAdmin() {
        return $this->role == self::ADMIN_ROLE;
    }

    public function isPermanent() {
        return $this->role >= self::PERMANENT_ROLE;
    }

    public static function form(): array
    {
        return [
            'firstName' => [
                'label' => 'Prénom',
                'type' => 'text',
                'required' => true
            ],
            'lastName' => [
                'label' => 'Nom',
                'type' => 'text',
                'required' => true
            ],
            'password' => [
                'label' => 'Password',
                'type' => 'password',
                'required' => true
            ],
            'email' => [
                'label' => 'Email',
                'type' => 'text',
                'required' => false
            ],
            'facebook' => [
                'label' => 'Facebook',
                'required' => false,
                'type' => 'text'
            ],
            'cip' => [
                'label' => 'CIP',
                'type' => 'text',
                'required' => false
            ],
            'isPermanent' => [
                'label' => 'Est un permanent',
                'type' => 'checkbox',
                'required' => false
            ],
            'isAdmin' => [
                'label' => 'Est un administrateur',
                'type' => 'checkbox',
                'required' => false
            ]
        ];
    }

}
