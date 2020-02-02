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
            $keyName = preg_replace_callback('/[A-Z]/', function($match) {
                return '_' . strtolower($match[0]);
            }, $key);
            $this->$key = isset($attrs[$keyName]) ? $attrs[$keyName] : NULL;
        }
    }

    /**
     * Computes the difference between an array of attributes and this instance's attributes
     *
     * @param array $attributes
     * @return array
     */
    public function diff(array $attributes): array {
        $diff = [];

        foreach (get_object_vars($this) as $key => $value) {
            if (isset($attributes[$key])) {
                if ($this->$key !== $attributes[$key]) {
                    $diff[$key] = $value;
                }
            }
        }

        return $diff;
    }


}
