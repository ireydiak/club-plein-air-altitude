<?php


namespace App\Database\Adapter;


use App\Domain\BaseDomain;
use App\Domain\Member;

class MemberAdapter implements Adaptable
{
    /**
     * @param BaseDomain $domain
     * @return array
     * @throws AdapterException
     */
    public static function toPDOStatement(BaseDomain $domain): array {
        if (!$domain instanceof Member) {
            throw new AdapterException(sprintf("%s is not an instance of App\\Member", get_class($domain)));
        }

        return array(
            'first_name'     => $domain->first_name,
            'last_name'      => $domain->last_name,
            'password'       => $domain->getPassword(),
            'is_permanent'   => $domain->isPermanent(),
            'is_admin'       => $domain->isAdmin(),
            'email'          => $domain->email,
            'facebook'       => $domain->facebook_link,
            'university_id'  => $domain->university_id
        );
    }

    public static function toDomain(array $row): BaseDomain {
        $row['university_id'] = $row['cip'];
        $member = new Member($row);
        $member->setRole((int) $row['is_permanent'] + (int) $row['is_admin']);

        return $member;
    }
}
