<?php


namespace App\Database;


class PDOBinding {

    public $param;
    public $value;
    public $type;

    public function __construct($param, $value, $type) {
        $this->param = $param;
        $this->value = $value;
        $this->type = $type;
    }

}
