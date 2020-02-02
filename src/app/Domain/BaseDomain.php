<?php


namespace App\Domain;


class BaseDomain
{
    /**
     * Compares two Domain objects
     *
     * @param BaseDomain $other
     * @return bool
     */
    public function equals(BaseDomain $other): bool {
        return $other === $this;
    }

    /**
     * Fills the instance's attributes from an associative array
     * Defaults to null if the value is not found in the array
     *
     * @param array $attrs
     */
    protected function fill(array $attrs): void {
        foreach (get_object_vars($this) as $key => $value) {
            $keyName = preg_replace_callback('/_([a-z]?)/', function($match) {
                return strtoupper($match[1]);
            }, $key);
            $this->$keyName = isset($attrs[$key]) ? $attrs[$key] : NULL;
        }
    }


}
