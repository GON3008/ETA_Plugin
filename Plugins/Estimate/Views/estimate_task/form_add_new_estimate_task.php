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
<script type="text/javascript">
    $(document).ready(function () {
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
