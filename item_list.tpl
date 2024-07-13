<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Item List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            background-color: #f4f4f9;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #fafafa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-edit,
        .btn-delete {
            margin-left: 10px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-edit {
            color: #007bff;
        }

        .btn-delete {
            color: #dc3545;
        }

        form {
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 12px;
            bottom: 15px;
            position: relative;
        }

        .file-link {
            margin-top: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            var base_url = '{$base_url}';

            function validateForm() {
                var isValid = true;
                $('#name-error, #description-error, #file-error').text('');

                var name = $('#name').val().trim();
                if (name.length === 0) {
                    $('#name-error').text('Name is required.');
                    isValid = false;
                } else if (name.length < 3) {
                    $('#name-error').text('Name must be at least 3 characters.');
                    isValid = false;
                }

                var description = $('#description').val().trim();
                if (description.length === 0) {
                    $('#description-error').text('Description is required.');
                    isValid = false;
                } else if (description.length < 10) {
                    $('#description-error').text('Description must be at least 10 characters.');
                    isValid = false;
                }

                var file = $('#file')[0].files[0];
                if (!file) {
                    $('#file-error').text('File is required.');
                    isValid = false;
                } else if (file.size > 2 * 1024 * 1024) {
                    $('#file-error').text('File size must be less than 2MB.');
                    isValid = false;
                }

                return isValid;
            }

            function loadItems() {
                $.ajax({
                    type: "GET",
                    url: base_url + '/index.php?get_items=true',
                    dataType: 'json',
                    success: function (data) {
                        if (data.items && data.items.length > 0) {
                            $('#itemsdisplaying ul').empty();
                            $.each(data.items, function (index, item) {
                                updateOrAppendItem(item);
                            });
                        } else {
                            $('#itemsdisplaying ul').empty();
                            console.log('No items found.');
                        }
                    },
                    error: function () {
                        alert('Error fetching items.');
                    }
                });
            }

            loadItems();

            $('#item-form').submit(function (e) {
                e.preventDefault();
                if (validateForm()) {
                    var form = $(this);
                    var actionUrl = form.attr('action');
                    var method = form.attr('method');
                    var formData = new FormData(this);

                    $.ajax({
                        url: actionUrl,
                        type: method,
                        data: formData,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (result) {
                            if (result.success) {
                                $('#item-form')[0].reset();
                                $('#submit-button').text('Create');
                                loadItems();
                            } else {
                                alert('Failed to ' + (method === 'POST' ? 'create' : 'update') + ' item: ' + result.error);
                            }
                        },
                        error: function () {
                            alert('Error ' + (method === 'POST' ? 'creating' : 'updating') + ' item.');
                        }
                    });
                }
            });

            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this item?')) {
                    var deleteUrl = $(this).attr('href');
                    $.ajax({
                        url: deleteUrl,
                        type: 'GET',
                        dataType: 'json',
                        success: function (result) {
                            if (result.success) {
                                removeItem(result.id);
                            } else {
                                alert('Failed to delete item: ' + result.error);
                            }
                        },
                        error: function () {
                            alert('Error deleting item.');
                        }
                    });
                }
            });

            $(document).on('click', '.btn-edit', function (e) {
                e.preventDefault();
                var editUrl = $(this).attr('href');
                $.ajax({
                    url: editUrl,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        if (data && data.item && data.item.id && data.item.name && data.item.description) {
                            $('#name').val(data.item.name);
                            $('#description').val(data.item.description);
                            $('#edit-id').val(data.item.id);
                            $('#item-form').attr('action', editUrl);
                            $('#submit-button').text('Update');
                            if (data.item.file_path) {
                                $('#file-info').html('<a href="' + data.item.file_path + '" target="_blank">View current file</a>');
                            } else {
                                $('#file-info').html('No file uploaded.');
                            }
                        } else {
                            alert('Item data is incomplete or missing.');
                        }
                    },
                    error: function () {
                        alert('Error fetching item for editing.');
                    }
                });
            });

            function updateOrAppendItem(item) {
                if (item && item.id && item.name && item.description) {
                    var existingItem = $('#itemsdisplaying ul li[data-id="' + item.id + '"]');
                    var fileLink = item.file_path ? '<br><a href="' + item.file_path + '" target="_blank">View File</a>' : '';
                    var itemContent = '<strong>' + item.name + '</strong> - ' + item.description + fileLink +
                        ' <a href="' + base_url + '/edit/' + item.id + '" class="btn-edit">Edit</a>' +
                        ' <a href="' + base_url + '/delete/' + item.id + '" class="btn-delete">Delete</a>';
                    if (existingItem.length > 0) {
                        existingItem.html(itemContent);
                    } else {
                        let newItem = '<li data-id="' + item.id + '">' + itemContent + '</li>';
                        $('#itemsdisplaying ul').append(newItem);
                    }
                } else {
                    console.error('Item data is incomplete or missing:', item);
                    alert('Item data is incomplete or missing. Failed to update item in the list.');
                }
            }

            function removeItem(id) {
                $('#itemsdisplaying ul li').each(function () {
                    if ($(this).data('id') == id) {
                        $(this).remove();
                        return false;
                    }
                });
            }
        });
    </script>
</head>

<body>
    <div class="container">
        <h1>Item List</h1>

        <div id="itemsdisplaying">
            <ul>
                {foreach $items as $item}
                <li data-id="{$item.id}">
                    <strong>{$item.name}</strong> - {$item.description}
                    {if $item.file_path}
                    <br><a href="{$item.file_path}" target="_blank">View File</a>
                    {/if}
                    <a href="{$base_url}/edit/{$item.id}" class="btn-edit">Edit</a>
                    <a href="{$base_url}/edit/{$item.id}" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                    <a href="{$base_url}/delete/{$item.id}" class="btn-delete"><i class="fas fa-trash-alt"></i> Delete</a>
                </li>
                {/foreach}
            </ul>
        </div>

        <form id="item-form" method="post" action="{$base_url}/create" enctype="multipart/form-data">
            <input type="hidden" id="edit-id" name="id">

            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <span id="name-error" class="error"></span>
            </div>

            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
                <span id="description-error" class="error"></span>
            </div>

            <div>
                <label for="file">File:</label>
                <input type="file" id="file" name="file" required>
                <span id="file-error" class="error"></span>
            </div>

            <div id="file-info" class="file-link"></div>

            <button type="submit" id="submit-button">Create</button>
        </form>
    </div>
</body>

</html>

