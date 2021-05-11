<?php


?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Test</title>
</head>
<body>
    <table>
        <thead>
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
        </tr>
        </thead>
        <tbody>
        <?php for ($i =0, $size = count($view['contacts']); $i < $size; $i++) : ?>
            <dl>
                <dt>Name</dt>
                <dd><?php echo $contact['first_name']; ?></dd>
                <dt>Name</dt>
                <dd><?php echo $contact['first_name']; ?></dd>
            </dl>
        <?php endfor; ?>
        </tbody>
    </table>

</body>
</html>
