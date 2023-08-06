<!DOCTYPE html>
<html>
<head>
    <title>Display Modified Image</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        h1 {
            margin-top: 20px;
            color: #333;
        }

        img {
            max-width: 100%;
            height: auto;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .error {
            color: #ff0000;
            font-size: 16px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Display Modified Image</h1>
    <?php
    // Check if the modifiedImage query parameter is set
    if (isset($_GET['modifiedImage'])) {
        $modifiedImage = $_GET['modifiedImage'];
        // Sanitize the modified image URL before displaying the image
        $modifiedImagePath = htmlspecialchars('/../images/modified/' . $modifiedImage);
        echo '<img src="' . $modifiedImagePath . '" alt="Modified Image">';
    } else {
        echo '<p class="error">Modified image not found.</p>';
    }
    ?>
</body>
</html>
