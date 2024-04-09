<?php

namespace Estimate\Models;

use App\Models\Crud_model;

class Estimate_task_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'estimates_task';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $estimates_task_table = $this->db->prefixTable('estimates_task');
        $users_table = $this->db->prefixTable('users');
        $confirm_estimate_task_table = $this->db->prefixTable('confirm_estimate_task');
        $where_task_id = '';
        $where_project_id = '';
        if (isset($options['project_id'])){
            $project_id = $options['project_id'];
            $where_project_id = ' AND '.$estimates_task_table.".project_id=".$project_id;
        }else{
            $where_project_id = '';
        }

        if(isset($options['task_id'])){
            $task_id = $options['task_id'];
            $where_task_id = ' AND '.$estimates_task_table.".task_id=".$task_id;
        }else{
            $where_task_id = '';
        }

        $sql = "select ".$estimates_task_table.".*,
                concat(".$users_table.".first_name, ' ', ".$users_table.".last_name) as user_full_name,
                ".$users_table.".image as avarta,
                ".$users_table.".email as email,
                ".$users_table.".phone as phone,
                ".$confirm_estimate_task_table.".confirm_at,
                ".$confirm_estimate_task_table.".confirmed,
                ".$confirm_estimate_task_table.".confirm_user_id,
                (SELECT concat(".$users_table.".first_name, ' ', ".$users_table.".last_name) FROM ".$users_table." WHERE ".$users_table.".id = ".$confirm_estimate_task_table.".confirm_user_id) as confirm_user_full_name,
                (SELECT ".$users_table.".image FROM ".$users_table." WHERE ".$users_table.".id = ".$confirm_estimate_task_table.".confirm_user_id) as confirm_user_avarta
                FROM ".$estimates_task_table."
                LEFT JOIN ".$users_table." ON ".$users_table.".id = ".$estimates_task_table.".user_id
                LEFT JOIN ".$confirm_estimate_task_table." ON ".$confirm_estimate_task_table.".estimate_id = ".$estimates_task_table.".id
                WHERE ".$estimates_task_table.".deleted=0 AND (".$confirm_estimate_task_table.".deleted = 0 OR ".$confirm_estimate_task_table.".deleted IS NULL)".$where_task_id.$where_project_id;
        return $this->db->query($sql);
    }
}
