<?php include('core/inside.php');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PHP Template System</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div id="centre">
            <div id="nav">
                <?php
                foreach ($pages as $page_name) {
                    echo '<a href="?page=', $page_name, '">', ucwords($page_name), '</a>';
                }
                ?>
            </div>
            <div id="wrap">
                <?php
                include($include_file);
                ?>
            </div>
        </div>
    </body>
</html>
