<?php

require_once dirname(__DIR__) . '/inc/debug.inc.php';
require_once dirname(__DIR__) . '/inc/bookmarks.inc.php';
require_once dirname(__DIR__) . '/inc/templates.inc.php';

$view = [];

$view['title'] = 'Bookmarks';
$view['template'] = 'index';
$view['bookmarks'] = find_all();

render($view);

// require dirname(__DIR__) . '/templates/base.html.php';

?>
