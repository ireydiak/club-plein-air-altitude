<?php


namespace App\Database\Adapter;


use App\Domain\BaseDomain;

interface Adaptable {

    /**
     * @param BaseDomain $domain
     * @throw AdapterException
     * @return array
     */
    public static function toPDOStatement(BaseDomain $domain): array;

    /**
     * @param array $row
     * @return BaseDomain
     */
    public static function toDomain(array $row): BaseDomain;
}
