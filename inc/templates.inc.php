<?php

function render(array $view, $layout = 'base') : void {
    $view['templates_dir'] = dirname(__DIR__) . '/templates';
    $view['templates_ext'] = '.html.php';

    require $view['templates_dir'] . '/' . $layout . $view['templates_ext'];
}