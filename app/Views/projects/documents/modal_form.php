<?php echo form_open(get_uri("documents/save"), array("id" => "document-form", "class" => "general-form", "role" => "form")); ?>
<div id="documents-dropzone" class="post-dropzone">
    <div class="modal-body clearfix">
        <div class="container-fluid">
            <input type="hidden" name="id" value="<?php echo $model_info->id; ?>"/>
            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>"/>
            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>"/>
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"/>
            <div class="form-group">
                <label><?php echo app_lang('document_level'); ?></label>
                <div>
                    <label class="radio-inline mr15">
                        <input type="radio" name="document_level" value="same_parent"
                               checked="checked"> <?php echo app_lang('same_level'); ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="document_level"
                               value="sub_parent"> <?php echo app_lang('sub_level'); ?>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <?php
                    echo form_input(array(
                        "id" => "title",
                        "name" => "title",
                        "value" => $model_info->title,
                        "class" => "form-control documentpad-title",
                        "placeholder" => app_lang('title'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group sub-level-fields" style="display: none;">
                <div class="col-md-12">
                    <?php
                    echo form_input(array(
                        "id" => "parent_id",
                        "name" => "parent_id",
                        "class" => "form-control",
                        "placeholder" => app_lang('parent_id'),
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group sub-level-fields pt-3" style="display: none;">
                <div class="col-md-12">
                    <div id="editor"
                    <label for="description"><?php echo app_lang('description'); ?></label>
                    <?php
                    echo form_textarea(array(
                        "id" => "description",
                        "name" => "description",
                        "class" => "form-control",
                        "placeholder" => app_lang('description') . "...",
                        "data-rich-text-editor" => true
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                <div class="documentpad">
                    <?php
                    echo form_input(array(
                        "id" => "document_labels",
                        "name" => "labels",
                        "value" => $model_info->labels,
                        "class" => "form-control",
                        "placeholder" => app_lang('labels')
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="modal-footer">

            <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x"
                                                                                        class="icon-16"></span> <?php echo app_lang('close'); ?>
            </button>
            <button type="submit" class="btn btn-primary"><span data-feather="check-circle"
                                                                class="icon-16"></span> <?php echo app_lang('save'); ?>
            </button>
        </div>
    </div>
    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#document-form").appForm({
                onSuccess: function (result) {
                    $("#document-table").appTable({newData: result.data, dataId: result.id});
                }
            });

            setTimeout(function () {
                $("#title").focus();
            }, 200);

            $("#document_labels").select2({multiple: true, data: <?php echo json_encode($label_suggestions); ?>});

            $('#parent_id').select2({
                data: <?php echo json_encode($parent_document); ?>
            });
            $('input[name="document_level"]').change(function () {
                var selectedValue = $(this).val();
                if (selectedValue === 'sub_parent') {
                    $('.sub-level-fields').hide();
                } else {
                    $('.sub-level-fields').hide();
                    if (selectedValue === 'same_parent') {
                        $('#parent_id').val('0');
                    }
                }
            });

        });
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            ClassicEditor.create(document.querySelector('#editor'), {
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        'link',
                        'bulletedList',
                        'numberedList',
                        '|',
                        'blockQuote',
                        'insertTable',
                        '|',
                        'undo',
                        'redo'
                    ]
                }
            }).then(editor => {
                editor.setData('<?php echo $model_info->description; ?>');
                editor.model.document.on('change:data', () => {
                    $("#description").val(editor.getData());
                });
            });
        });
    </script>
