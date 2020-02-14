<?php

namespace App\Domain;

class Member extends BaseDomain {
    /**
     * @var int
     */
    public $memberId;

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
    public $password;

    /**
     * @nullable
     * @var string
     */
    public $cip;

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
     * Date de création du membre
     *
     * @var string
     */
    public $created_at;

    /**
     * Date de modification du membre
     *
     * @var  string
     */
    public $updated_at;

    /**
     * Numéro de téléphone du membre
     *
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $role;

    /**
     * @var
     */
    public $membership;

    /**
     * If the member has an active membership or not
     *
     * @var boolean
     */
    public $isActive;

    /**
     * @var int
     */
    public const REGULAR_ROLE = 'Membre';

    /**
     * @var int
     */
    public const PERMANENT_ROLE = 'Permanent';

    /**
     * @var int
     */
    public const ADMIN_ROLE = 'Admin';

    public function __construct($attributes) {
        $this->fill($attributes);
        $this->isActive = $this->defaultIfNotSet($attributes, 'is_active', false);
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

        return $this->firstName == $other->firstName &&
            $this->lastName == $other->lastName &&
            $this->email == $other->email;
    }

    public function setRole(int $role) {
        switch ($role) {
            case 0:
                $this->role = self::REGULAR_ROLE;
                break;
            case 1:
                $this->role = self::PERMANENT_ROLE;
                break;
            case 2:
                $this->role = self::ADMIN_ROLE;
                break;
            default:
                $this->role = self::REGULAR_ROLE;
        }
    }

    public function isAdmin() {
        return $this->role == self::ADMIN_ROLE;
    }

    public function isPermanent() {
        return $this->role >= self::PERMANENT_ROLE;
    }

}
