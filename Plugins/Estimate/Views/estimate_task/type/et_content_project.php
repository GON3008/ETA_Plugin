<?php
if(isset($instance)){
    $user_id = $instance->login_user->id;
}else{
    $user_id = '';
}
if(isset($task_data) && isset($task_data->id)){
    $task_id = $task_data->id;
}else{
    $uri_string = uri_string();
    $url_task_view = 'tasks/view/';
    $checkurl = strpos($uri_string, $url_task_view);
    if ($checkurl !== false) {
        $task_id = str_replace($url_task_view,'',$uri_string);
    }else{
        if($uri_string == 'tasks/view' && isset($_POST['id'])){
            $task_id = $_POST['id'];
        }
    }
    $task_id = isset($task_id) ? $task_id : 0;
}

$estimate_id_prefix = "task-";
?>

<div class="table" id="task-estimate-data-table-container">
    <table id="task-estimate-data-project" class="display" cellspacing="0" width="100%">
    </table>
</div>
<script type="text/javascript">
    updateEstimateTasks = function (url_string) {
        // $.ajax({
        //     url: url_string,
        //     dataType: "json",
        //     success: function (result) {
        //         console.log('1111');
        //     }
        // });
    };

    $(document).ready(function (){
        $("#task-estimate-data-project").appTable({
            source: '<?php echo_uri("estimate_task/list_data_project") ?>/' + <?php echo $task_id ?>,
            serverSide: true,
            responsive: true, //hide responsive (+) icon
            hideTools: true,
            columns: [
                {title: '<?php echo app_lang("estimate_task_user_eta_name") ?>',"class": "idColumnClass", order_by: "user_full_name"},
                {title: '<?php echo app_lang("estimate_task_requested").'(h)' ?>',order_by: "time"},
                {title: '<?php echo app_lang("estimate_task_user_confirmed_full_name") ?>', order_by: "confirm_user_full_name"},
                {title: '<?php echo app_lang("confirm_at") ?>'},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option "}
            ],
            printColumns: combineCustomFieldsColumns([0,1, 2, 3, 4]),
            onInitComplete: function () {
                appLoader.hide();
            }
        });



        //refresh reminders on click any action
        $("body").on("click", ".estimate-task-action", function () {
            var self = $(this);
            setTimeout(function () {
                if (typeof updateEstimateTasks === 'function') {
                    updateEstimateTasks(self.attr('data-action-url'));
                }
            }, 5000);
        });

    });


</script>
