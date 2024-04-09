<?php

namespace Estimate\Models;

use App\Models\Crud_model;

class Estimate_confirmed_task_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'confirm_estimate_task';
        parent::__construct($this->table);
    }

    public function ci_save(&$data = array(), $id = 0) {
        return parent::ci_save($data,$id);
    }
}
