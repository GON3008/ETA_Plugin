<?php
if(isset($instance)){
    $user_id = $instance->login_user->id;
}else{
    $user_id = '';
}
$uri_string = uri_string();
$url_task_view = 'tasks/view/';
$checkurl = strpos($uri_string, $url_task_view);
if ($checkurl !== false) {
    $task_id = str_replace($url_task_view,'',$uri_string);
}
$task_id = isset($task_id) ? $task_id : 0;

$estimate_id_prefix = "task-";
?>

<?php
echo js_anchor(app_lang("estimate_task_add"), array("id" => $estimate_id_prefix . "show-add-estimate-form", "class" => "inline-block mb15 btn btn-primary"));
?>

<div id="<?php echo $estimate_id_prefix . 'estimate-form-container'; ?>" class="<?php echo "hide"; ?>">
    <?php echo form_open(get_uri("estimate_task/save"), array("id" => $estimate_id_prefix . "estimate_form", "class" => "general-form", "role" => "form")); ?>
    <input type="hidden" name="type" value="estimate_task"/>
    <input type="hidden" name="task_id" value="<?php echo $task_id; ?>"/>
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"/>
    <input type="hidden" name="user_confirm_id" value="" />
    <div class="form-group">
        <div class="mt5 p0">
            <?php
            echo form_input(array(
                "id" => $estimate_id_prefix . "time",
                "name" => "time",
                "class" => "form-control",
                "placeholder" => app_lang('time_estimate_task'),
                "autocomplete" => "off",
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="mb20 p0">
        <button type="submit" class="btn btn-primary w100p">
            <span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('add'); ?>
        </button>
    </div>
</div>

<?php echo view("Estimate\Views\\estimate_task\\type\\data_table", array($task_id)); ?>


<div class="table-responsive">
    <table id="<?php echo $estimate_id_prefix . 'estimate-table'; ?>" class="display no-thead b-t b-b-only no-hover" cellspacing="0" width="100%">
    </table>
</div>


<script type="text/javascript">
    var estimateTableId = "#<?php echo $estimate_id_prefix . 'estimate-table'; ?>";
    var $tableEstimateSelector = $(estimateTableId);

    $(document).ready(function () {
        <?php if($task_id): ?>
            $('body').on('click', "#show-all-estimate-btn", function () {
                loadDataEstimateTable("all");
                appLoader.show({container: estimateTableId, css: "left:0; top:170px"});
                $(this).addClass("disabled");
            });

        function loadDataEstimateTable() {
                $tableEstimateSelector.appTable({
                    source: '<?php echo_uri("estimate_task/list_data") ?>/' + <?php echo $task_id ?>,
                    hideTools: true,
                    order: [[0, "asc"]],
                    displayLength: 100,
                    columns: [
                        {title: '<?php echo "data 1"; ?>', "class": "estimate-task-title-section"},
                        {title: '<?php echo "data 2"; ?>', "class": "estimate-task-title-section"},
                    ],
                    onInitComplete: function () {
                        appLoader.hide();
                    }
                });
            };
        setTimeout(function(){
            // debugger;
            loadDataEstimateTable('all');
        }, 100);
        <?php endif; ?>
        //show ETA form
        $("#<?php echo $estimate_id_prefix . 'show-add-estimate-form'; ?>").click(function () {
            $(this).addClass("hide");
            $("#<?php echo $estimate_id_prefix . 'estimate-form-container'; ?>").removeClass("hide");
        });

        $("#<?php echo $estimate_id_prefix . 'estimate_form'; ?>").appForm({
            isModal: false,
            onSuccess: function (result) {
                console.log(result);
            }
        });
    });
</script>
