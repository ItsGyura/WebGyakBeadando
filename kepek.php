
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fő oldal</title>
    <link rel="stylesheet" href="style.css">
    <script src="menu.js"></script>
</head>
<body>
<div id="galeria">
    <h1>Galéria</h1>
    <div>
        <?php
        $directory = "kepek/";
        $images = glob($directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

        foreach($images as $image) {
            echo '<img src="'.$image.'">';
        }
        ?>
    </div>
    </div>
</body>
</html>