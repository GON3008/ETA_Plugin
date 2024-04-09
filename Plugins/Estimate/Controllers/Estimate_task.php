<?php
namespace Estimate\Controllers;

use App\Controllers\Security_Controller;

class Estimate_task extends Security_Controller {

    /**
     * @var \Estimate\Models\Estimate_task_model
    **/
    protected $estimateTaskModel;

    /**
     * @var \Estimate\Models\Estimate_confirmed_task_model
    **/
    protected $confirmedEstimateTaskModel;

    /**
     * Create new contruc extent form security system
    */
    public function __construct() {
        parent::__construct();
        $this->estimateTaskModel = new \Estimate\Models\Estimate_task_model();
        $this->confirmedEstimateTaskModel = new \Estimate\Models\Estimate_confirmed_task_model();
    }

    public function index()
    {
        die('aaa');
        var_dump('aaa');
        die;
    }

    /**
     * Function check access to estimate time for task
     *
     * @return bool
    */
    private function can_access_this_estimate_task() : bool {
        $permissions = $this->login_user->permissions;

        if ($this->login_user->is_admin) {
            return true;
        } else if (get_array_value($permissions, "estimate_task") == "all") {
            return true;
        } else if (get_array_value($permissions, "estimate_task_confirm")) {
            return true;
        }

        return false;
    }

    /**
     * router post method estimate_task/confirm/$any
     * this is router help we are save date for table in database_config_prefix.""._confirm_estimate_task
     *
     * @params int $estimate_id
    */
    public function confirmed($estimate_id)
    {
        //check estimate_task access permission for client
        if ($this->login_user->user_type === "client" && $this->login_user->user_type) {
            if (!$this->can_access_this_estimate_task()) {
                app_redirect("forbidden");
            }
        }

        // provider data for insert data to table database_config_prefix.""._confirm_estimate_task
        $data = array(
            "estimate_id" => $estimate_id,
            "confirm_user_id" => $this->login_user->id,
            "confirm_at" => get_current_utc_time(),
            "confirmed" => 1,
        );

        // clean all data before insert to database
        $data = clean_data($data);

        // saved to database_config_prefix.""._confirm_estimate_task and return to ID record
        $save_id = $this->confirmedEstimateTaskModel->ci_save($data, 0);

        if ($save_id) {
            log_notification("new_event_added_in_calendar", array("event_id" => $save_id));
            echo json_encode(array("success" => true, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /**
     * router post method estimate_task/list_data_table/$any
     * this is router help we are get all data form database with all user info and return to json data for client ajax
     *
     * @params int $task_id
     */
    public function list_data_table($task_id)
    {
        $options["task_id"] = $task_id;

        $list_data = $this->estimateTaskModel->get_details($options)->getResult();

        $result = array();
        foreach ($list_data as $data) {
            // format data one more time for ajax data table
            $result[] = $this->_make_estimate_table_row($data);
        }
        // encode data to json provider for client result
        echo json_encode(array("data" => $result));
    }

    public function list_data_project()
    {
         $this->estimateTaskModel->get_details()->getResult();

//        $result = array();
//        foreach ($list_data as $data) {
//            // format data one more time for ajax data table
//            $result[] = $this->_make_eta_row($data);
//        }
//        echo json_encode(array("data" => $result));
    }


    /**
     * This is private function only support for Estimate time on task
     * and this is support format data again before return to client
     *
     * @params std $data this is stdclass record form database
     * @return array $data
    */
    public function _make_estimate_table_row($data) : array {
        $dataRespon = array(
            $data->user_full_name,
            $data->time,
            $data->confirm_user_full_name,
            $data->confirm_at ? format_to_relative_time($data->confirm_at) : '',
        );

        // format link disable estimate ajax function on client
        $delete = js_anchor("<i data-feather='x' class='icon-20'></i>" ,
            array(
                'title' => app_lang('estimate_task_delete'),
                "class" => "delete dropdown-item estimate-task-action w25",
                "data-id" => $data->id, "data-post-id" => $data->id,
                "data-action-url" => get_uri("estimate_task/disable_task_estimate/$data->id"),
                "data-act"=>"delete",
                "data-action" => "delete", "data-undo" => "0"));
        $status = '';
        // only show link deleted for admin
        if (!$this->login_user->is_admin) {
            $delete = '';
        }

        // format link accep estimate ajax function on client
        if(!isset($data->confirmed) || $data->confirmed === 'null' || $data->confirmed == 0){
            $status = js_anchor("<i data-feather='check-circle' class='icon-20'></i> ",
                array(
                    'title' => app_lang('estimate_task_mark_as_confirmed'),
                    "class" => "dropdown-item estimate-task-action w25",
                    "data-action-url" => get_uri("estimate_task/confirmed/$data->id"),
                    "data-action" => "delete",
                    "data-undo" => "0",
                    "data-act"=>"delete"
                )
            );

        }
        // format data done
        $dataRespon[] = $delete.$status;

        return $dataRespon;
    }

     function _make_eta_row($data) : array {
        $dataRespon = array(
            $data->user_full_name,
            $data->time,
            $data->confirm_user_full_name,
            $data->confirm_at ? format_to_relative_time($data->confirm_at) : '',
        );
        // format link disable estimate ajax function on client
        $delete = js_anchor("<i data-feather='x' class='icon-20'></i>" ,
            array(
                'title' => app_lang('estimate_task_delete'),
                "class" => "delete dropdown-item estimate-task-action w25",
                "data-id" => $data->id, "data-post-id" => $data->id,
                "data-action-url" => get_uri("estimate_task/disable_task_estimate/$data->id"),
                "data-act"=>"delete",
                "data-action" => "delete", "data-undo" => "0"));
        $status = '';
        // only show link deleted for admin
        if (!$this->login_user->is_admin) {
            $delete = '';
        }

        // format link accep estimate ajax function on client
        if(!isset($data->confirmed) || $data->confirmed === 'null' || $data->confirmed == 0){
            $status = js_anchor("<i data-feather='check-circle' class='icon-20'></i> ",
                array(
                    'title' => app_lang('estimate_task_mark_as_confirmed'),
                    "class" => "dropdown-item estimate-task-action w25",
                    "data-action-url" => get_uri("estimate_task/confirmed/$data->id"),
                    "data-action" => "delete",
                    "data-undo" => "0",
                    "data-act"=>"delete"
                )
            );

        }
        // format data done
        $dataRespon[] = $delete.$status;

        return $dataRespon;
    }

    /**
     * router post method estimate_task/list_data/$any
     * this is router help we are get all data form database with all user info and return to json data for client ajax
     *
     * @params int $task_id
     */
    public function list_data($task_id){
        $options["task_id"] = $task_id;

        $list_data = $this->estimateTaskModel->get_details($options)->getResult();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_estimate_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    /**
     * This is private function only support for Estimate time on task
     * and this is support format data again before return to client
     *
     * @params std $data this is stdclass record form database
     * @return array $data
     */
     protected function _make_estimate_row($data){
        $title = '<span class="missed-reminder">'.
                        '<a href="#"><i data-feather="check-circle" class="icon-16"></i></a> '.
                        link_it($data->user_full_name . app_lang('estimate_task_requested') .$data->time.'h').
                        '<div class="small">'.
                            $data->confirm_user_full_name. ' '.app_lang('estimate_task_confirm_at').format_to_relative_time($data->confirm_at).
                        '</div>'.
                '</span>';

        $delete = '<li role="presentation">' .
                        js_anchor("<i data-feather='x' class='icon-16'></i>" . app_lang('delete'),
                        array(
                            'title' => app_lang('estimate_task_delete'),
                            "class" => "delete dropdown-item reminder-action",
                            "data-id" => $data->id, "data-post-id" => $data->id,
                            "data-action-url" => get_uri("estimate_task/disable_task_estimate/$data->id"),
                            "data-action" => "delete", "data-undo" => "0")) .
            '</li>';
        $status = '';
        if (!$this->login_user->is_admin) {
            $delete = '';
        }
        if(!isset($data->confirmed) || $data->confirmed === 'null' || $data->confirmed == 0){
            $status = '<li role="presentation">' . js_anchor("<i data-feather='check-circle' class='icon-16'></i> " . app_lang('estimate_task_mark_as_confirmed'), array('title' => app_lang('estimate_task_mark_as_confirmed'), "class" => "dropdown-item reminder-action", "data-action-url" => get_uri("estimate_task/confirmed/$data->id"), "data-action" => "delete", "data-undo" => "0")) . '</li>';
            $title = '<span class="missed-reminder">'.
                '<i data-feather="info" class="icon-16"></i> '.
                    link_it($data->user_full_name. app_lang('estimate_task_requested') .$data->time.'h').
                '</span>';
        }
        $options = '<span class="dropdown inline-block">
                        <div class="dropdown-toggle clickable p10" type="button" data-bs-toggle="dropdown" aria-expanded="true" data-bs-display="static">
                            <i data-feather="more-horizontal" class="icon-16"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end" role="menu">' . $status . $delete . '</ul>
                    </span>';
        return array(
            $title,
            $options
        );
    }

    /**
     * Delete event this is function and controller router only for admin
     *
     * @param $id
     */
    public function disable_task_estimate($id)
    {
        //check estimate_task access permission for admin
        $this->access_only_admin();

        $save_id = $this->estimateTaskModel->delete($id);
        if ($save_id) {
            log_notification("new_event_added_in_calendar", array("event_id" => $save_id));
            echo json_encode(array("warining" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /**
     * save event this is function and controller router provider for anyone
     * able to send request ETA for a task
     *
     * NOTE : this function requried post data task_id and time
     */
    public function save() {
        $type = $this->request->getPost('type');
        $validation_array = array(
            "task_id" => "required",
            "user_id" => "required",
            "time" => "required"
        );

        //check estimate_task access permission for client
        if ($this->login_user->user_type === "client" && $type !== "estimate_task") {
            if (!$this->can_client_access("estimate_task")) {
                app_redirect("forbidden");
            }
        }


        $this->validate_submitted_data($validation_array);

        $id = $this->request->getPost('id');

        $data = array(
            "task_id" => $this->request->getPost('task_id'),
            "user_id" => $this->login_user->id,
            "time" => $this->request->getPost('time'),
        );



        $data = clean_data($data);

        $save_id = $this->estimateTaskModel->ci_save($data, $id);
        if ($save_id) {
            log_notification("new_event_added_in_calendar", array("event_id" => $save_id));
            echo json_encode(array("success" => true, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }


}
