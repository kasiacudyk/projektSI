<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <title><?php echo $view['title'] ?? '404 - page not found'; ?></title>
    </head>
    <body>
    <?php require $view['templates_dir'] . '/' . ($view['template'] ?? '404') . $view['templates_ext']; ?>

    </body>
</html>
