<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{if isset($item)}Edit{else}Create{/if} Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        form {
            max-width: 600px;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        a.btn-cancel {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>{if isset($item)}Edit{else}Create{/if} Item</h1>
    <form method="post" action="{$base_url}/create">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{$item.name|default:''}" required><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description">{$item.description|default:''}</textarea><br>
        <button type="submit">{if isset($item)}Update{else}Create{/if}</button>
    </form>
    <a href="{$base_url}" class="btn-cancel">Back to List</a>
</body>
</html>
