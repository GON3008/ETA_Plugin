<div class="modal-body clearfix general-form">
    <div class="container-fluid">
        <div class="form-group">
            <div class="col-md-12 notepad-title">
                <strong class=""><?php echo $model_info->title; ?></strong>
            </div>
        </div>

        <div class="col-md-12">
            <?php echo make_labels_view_data($model_info->labels_list); ?>
        </div>

    </div>
</div>

<div class="modal-footer justify-content-center">
    <?php if ($model_info->created_by == $login_user->id || $login_user->is_admin) : ?>
        <?php echo modal_anchor(get_uri("documents/modal_form"), "<i data-feather='edit-2' class='icon-16'></i> " . app_lang('edit_document'), array("class" => "btn btn-default", "data-post-id" => $model_info->id, "title" => app_lang('edit_document'))); ?>
        <button type="button" class="btn btn-danger" id="delete-document"
                data-document-id="<?php echo $model_info->id; ?>"><i data-feather='trash-2'
                                                                     class='icon-16'></i> <?php echo app_lang('delete'); ?>
        </button>
    <?php endif; ?>
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x"
                                                                                class="icon-16"></span> <?php echo app_lang('close'); ?>
    </button>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        // Handle delete event when the user clicks on the delete button
        $("#delete-document").click(function () {
            var documentId = $(this).data("document-id");
            appLoader.show();

            // Send delete request via Ajax
            $.ajax({
                url: "<?php echo get_uri('documents/delete') ?>",
                type: 'POST',
                data: {id: documentId},
                dataType: 'json',
                success: function (result) {
                    if (result.success) {
                        // If deletion is successful, reload the page or perform other actions
                        location.reload();
                    } else {
                        // Handle errors if needed
                        appAlert.error(result.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // Handle Ajax errors if needed
                    appAlert.error('<?php echo app_lang("error_occurred"); ?>');
                },
                complete: function () {
                    appLoader.hide();
                }
            });
        });
    });
</script>
