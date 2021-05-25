<?php
require_once dirname(__FILE__) . '/debug.inc.php';

$persons = [
    [
        'first_name' => 'Mark',
        'surname' => 'Brown',
        'age' => '21',
    ],
    [
        'first_name' => 'John',
        'surname' => 'Doe',
        'age' => '22',
    ],
    [
        'first_name' => 'Ann',
        'surname' => 'Smith',
        'age' => '18',
    ],
];

# tablica pomocnicza z kluczami
$keys = [
    'first_name' => 'Imię',
    'surname' => 'Nazwisko',
    'age' => 'Wiek',
]

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Arrays</title>
    </head>
    <body>
        <ul>
            <?php foreach ($persons as $person): ?>
            <ul>
                <?php foreach ($person as $key => $value): ?>
                <?php
                    $label = isset($keys[$key]) ? $keys[$key] : $key;
                    echo $label . ': ' . $value;
                ?><br />
                <?php endforeach; ?>
            </ul>
            <?php endforeach; ?>
        </ul>
    </body>
</html>
