<?php
require_once dirname(__DIR__) . '/inc/debug.inc.php';
require_once dirname(__DIR__) . '/inc/templates.inc.php';

$view = [];
$view['template'] = 'hello-world';

$name = !empty($_GET['name']) ? $_GET['name'] : 'World';
$greeting = 'Hello ' . $name . '!';

$view['title'] = $greeting;
$view['greeting'] = $greeting;

render($view);