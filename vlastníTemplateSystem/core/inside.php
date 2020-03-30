<?php

if (empty($_GET['page'])) {
    header('Location: ?page=home');
    die();
}
$core_path = dirname(__FILE__);
$pages = scandir("{$core_path}/pages");
unset($pages[0], $pages[1]);
foreach ($pages as &$page) {
    $page = substr($page, 0, strpos($page, '.'));
}
if (in_array($_GET['page'], $pages)) {
    $include_file = "{$core_path}/pages/{$_GET['page']}.page.php";
} else {
    $include_file = "{$core_path}/pages/home.page.php";
}