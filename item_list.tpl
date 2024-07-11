<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Item List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
        .btn-edit, .btn-delete {
            margin-left: 10px;
            text-decoration: none;
            color: blue;
        }
        .btn-delete {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Item List</h1>
    <a href="{$base_url}/create">Create New Item</a>
    <ul>
        {foreach $items as $item}
            <li>
                <strong>{$item.name}</strong> - {$item.description}
                <a href="{$base_url}/edit/{$item.id}" class="btn-edit">Edit</a>
                <a href="{$base_url}/delete/{$item.id}" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
            </li>
        {/foreach}
    </ul>
</body>
</html>
