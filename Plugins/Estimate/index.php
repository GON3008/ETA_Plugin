<?php
defined('PLUGINPATH') or exit('No direct script access allowed');

/*
  Plugin Name: Estimate time task
  Description: Help we are build more function Estimate time for task and tickets inside RISE CRM.
  Version: 1.0
  Requires at least: 3.6
  Author: Vũ Gia Software Việt name
  Author URL: https://vugiasoftware.com/
 */

use App\Controllers\Security_Controller;

app_hooks()->add_action('app_hook_task_view_right_panel_extension', function () {
//    $instance = new Security_Controller();
//    include  PLUGINPATH . "Estimate/Views/estimate_task/btn.php";
});

/**
 * this is customization for hook added to core view app/Views/tasks/task_view_data.php
 * on this file we are added hook function and provider data for something else extent on future
 * $data already data task_data this is task_model on this file
 */
app_hooks()->add_action('app_hook_task_view_left_panel_extension', function ($data) {
//    $instance = new Security_Controller();
//    include  PLUGINPATH . "Estimate/Views/estimate_task/btn.php";
});

/**
 * this is customization for hook added to core view app/Views/tasks/task_view_data.php
 * on this file we are added hook function and provider data for something else extent on future
 * $data already data task_data this is task_model on this file
 * @params $data this is data provider on acion do_action on view app/Views/tasks/task_view_data.php
 *
 *function help we are include more customization source code to task detail
 */
app_hooks()->add_action('app_hook_task_view_content_panel_extension', function ($data) {
    if (isset($data['task_data'])) {
        $task_data = $data['task_data'];
    }
    $instance = new Security_Controller();
    include PLUGINPATH . "Estimate/Views/estimate_task/type/full_content_task.php";
});

app_hooks()->add_action('app_hook_estimate_content_view_project_extension', function ($data) {
    if (isset($data['task_data'])) {
        $task_data = $data['task_data'];
    }
    $instance = new Security_Controller();
    include PLUGINPATH . "Estimate/Views/estimate_task/type/et_content_project.php";
});

app_hooks()->add_action('app_hook_columns_extension', function ($data) {
    if (isset($task_data)){
        $task_data = $data['task_data'];
    }
    $instance = new Security_Controller();
    include PLUGINPATH . "Estimate/Views/estimate_task/type/columns_eta.php";
});

app_hooks()->add_action('app_hook_list_data_eta_extension', function ($data) {
    if (isset($task_data)){
        $task_data = $data['task_data'];
    }
    $instance = new Security_Controller();
    include PLUGINPATH . "Estimate/Views/estimate_task/type/list_data_eta.php";
});

app_hooks()->add_action('app_hook_et_content_extension', function ($data) {
    if (isset($data['task_data'])) {
        $task_data = $data['task_data'];
    }
    $instance = new Security_Controller();
    include PLUGINPATH . "Estimate/Views/estimate_task/type/et_content_project.php";
});
app_hooks()->add_action('app_hook_eta_list_data_extension', function () {
    $eta_data = new Estimate\Controllers\Estimate_task();
    $eta_data->list_data_project();
});

app_hooks()->add_action('app_hook_make_row_eta_extension', function ($data) {
    $eta_row = new Estimate\Controllers\Estimate_task();
    $eta_row->make_row_eta($data);
});


////install dependencies
register_installation_hook("Estimate", function ($item_purchase_code) {
    include PLUGINPATH . "Estimate/install/do_install.php";
});


//uninstallation: remove data from database
register_uninstallation_hook("Estimate", function () {
    $dbprefix = get_db_prefix();
    $db = db_connect('default');

    $sql_query = "DROP TABLE IF EXISTS `" . $dbprefix . "prefix_demo_settings`;";
    $db->query($sql_query);
});

