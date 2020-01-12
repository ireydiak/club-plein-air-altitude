<?php

namespace App\Domain;

class Member extends BaseDomain
{
    /**
     * @var int
     */
    protected $member_id;

    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var string
     */
    protected $password;

    /**
     * @nullable
     * @var string
     */
    public $university_id;

    /**
     * @nullable
     * @var string
     */
    public $facebook_link;

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
        return $this->member_id;
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
        return $this->first_name    == $other->first_name &&
            $this->last_name        == $other->last_name &&
            $this->email            == $other->email &&
            $this->facebook_link    == $other->facebook_link &&
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

    /**
     * TODO: Use better validation technique
     * @return bool
     */
    public function validate() {
        return !empty($this->first_name) &&
            !empty($this->last_name) &&
            !empty($this->password) &&
            (!empty($this->university_id) || !empty($this->email) || !empty($this->facebook_link));
    }
}
