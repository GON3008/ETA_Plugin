<div class="row">
    <div class="card col-md-4">
        <div class="tab-title clearfix">
            <h4><?php echo app_lang('documents') ?></h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("documents/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_document'), array("class" => "btn btn-default", "title" => app_lang('add_document'), "data-post-project_id" => $project_id)); ?>
            </div>
        </div>
        <div class="table-responsive">
            <div id="treeview"></div>
        </div>
    </div>
    <div class="col-md-8">
        <div id="message-details-section" class="panel card">
            <div id="document-content" class="text-center mb15 box">
                <div class="box-content" style="vertical-align: middle; height: 100%">
                    <div><?php echo app_lang("select_a_document"); ?></div>
                    <span class="bi bi-file-earmark" style="font-size: 1100%; color:#f6f8f8"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var project_id = <?php echo json_encode($project_id); ?>;
        var treeUrl = "<?php echo_uri('documents/list_tree/project/' . $project_id) ?>";

        // Make an AJAX request to fetch the tree data
        $.ajax({
            url: treeUrl,
            method: "GET",
            dataType: "json",
            success: function (data) {
                // Filter out empty sub-arrays and flatten the data
                var flatData = data.filter(function (item) {
                    return item.length > 0;
                }).reduce(function (acc, val) {
                    return acc.concat(val);
                }, []);

                // Convert the data to the appropriate JSON format for the Treeview plugin
                var treeData = flatData.map(function (item) {
                    return {
                        text: item.text,
                        id: item.id, // Add id property
                        nodes: item.nodes || [],
                        openIcon: item.openIcon, // Add openIcon property
                        addIcon: item.addIcon
                    };
                });

                console.log(treeData);
                // Once the data is fetched successfully and processed, display the tree
                displayTree(treeData, document.getElementById('treeview'));
            },
            error: function (xhr, status, error) {
                // Handle error if AJAX request fails
                console.error("Error fetching tree data:", error);
            }
        });

        // Function to display the tree
        function displayTree(data, parentElement, isChildNode = false) {
            var ul;
            if (!isChildNode) {
                ul = document.createElement('ul');
            } else {
                ul = parentElement.querySelector('ul');
                if (!ul) {
                    ul = document.createElement('ul');
                    parentElement.appendChild(ul);
                }
            }
            data.forEach(function (node) {
                var li = document.createElement('li');
                li.dataset.documentId = node.id; // Set the data-document-id attribute with the document ID

                var titleNode = document.createElement('span');
                titleNode.textContent = node.text;
                titleNode.style.cursor = 'pointer'; // Add cursor style
                li.appendChild(titleNode);

                var isOpenIconClicked = false; // Initialize the flag

                if (node.openIcon) { // Check if openIcon exists
                    var openIcon = document.createElement('i');
                    openIcon.classList.add('bi', 'bi-folder2-open'); // Add classes for Bootstrap icons
                    openIcon.style.cursor = 'pointer'; // Add cursor style
                    openIcon.addEventListener('click', function () {
                        isOpenIconClicked = true; // Set the flag to true when openIcon is clicked
                        var documentId = node.id; // Get the document ID associated with the clicked open icon
                        loadDocumentContent(documentId);
                    });
                    li.appendChild(openIcon);
                }

                var isAddIconClicked = false; // Initialize the flag

                if (node.addIcon) { // Check if addIcon exists
                    var addIcon = document.createElement('i');
                    addIcon.classList.add('bi', 'bi-plus-circle'); // Add classes for Bootstrap icons
                    addIcon.style.cursor = 'pointer'; // Add cursor style
                    addIcon.addEventListener('click', function (event) {
                        event.stopPropagation(); // Prevent event propagation to the parent node
                        isAddIconClicked = true; // Set the flag to true when addIcon is clicked
                        var addId = node.id;
                        loadDocumentAdd(addId);
                    });
                    li.appendChild(addIcon);
                }

                // Add click event listener to the titleNode
                titleNode.addEventListener('click', function () {
                    var documentId = node.id; // Get the document ID associated with the clicked title
                    loadDocumentContent(documentId);
                });

                // Check if node has child nodes
                if (node.nodes && node.nodes.length > 0) {
                    li.classList.add('has-children');
                    li.addEventListener('click', function () {
                        if (!isOpenIconClicked) { // Check if openIcon is not clicked
                            var childNodes = this.querySelector('ul');
                            childNodes.style.display = childNodes.style.display === 'none' ? 'block' : 'none';
                        }
                        if (!isAddIconClicked) { // Check if addIcon is not clicked
                            var childadd = this.querySelector('ul');
                            childadd.style.display = childadd.style.display === 'none' ? 'block' : 'none';
                        }
                        isOpenIconClicked = false; // Reset the flag after handling the click event
                        isAddIconClicked = false; // Reset the flag after handling the click event
                    });
                    // Recursively call displayTree to display child nodes
                    displayTree(node.nodes, li, true);
                }

                ul.appendChild(li);
            });
            // Append ul to parentElement if it's not a child node
            if (!isChildNode) {
                parentElement.appendChild(ul);
            }
        }

        // Function to load document content
        function loadDocumentContent(documentId) {
            var documentUrl = "<?php echo get_uri('documents/view/'); ?>" + documentId;
            $.ajax({
                url: documentUrl,
                method: "GET",
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    $('#document-content').html(response.data); // Load the document content into the #document-content div
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching document data:", error);
                }
            });
        }

        function loadDocumentAdd(addId) {
            var parentId = addId;
            var documentUrl = "<?php echo get_uri('documents/modal_form/'); ?>";
            var dataPost = {
                project_id: <?php echo $project_id; ?>,
                parent_id: parentId,
            };
            console.log(parentId);
            $.ajax({
                url: documentUrl,
                method: "POST",
                data: dataPost,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    $('#document-content').html(response.data);
                    $('#parent_id').val(parentId);
                }
            });
        }

    });
</script>
