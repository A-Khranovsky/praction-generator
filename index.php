<?php

require_once 'vendor/autoload.php';

$fileName = __DIR__ . '/data.txt';
$content = '';

function shatter($fileName)
{
    $file = fopen($fileName, 'r');
    while (!feof($file)) {
        $str = fgets($file);
        yield $str;
    }
    fclose($file);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['fake_data_write'])) {
        $faker = Faker\Factory::create();
        $text = '';
        for ($i = 0; $i < 60; $i++) {
            $text .= $faker->text(100) . PHP_EOL;
        }
        file_put_contents(
            __DIR__ . '/data.txt',
            $text
        );
    }
    $content = '<a href="/?text">See the text</a>';
} else {
    if ($_SERVER['QUERY_STRING'] == 'text') {
        $content = file_get_contents($fileName);
        $content .= "<br /><a href='/?shatter'>String shatte</a>";
    }
    if ($_SERVER['QUERY_STRING'] == 'shatter') {
        foreach (shatter($fileName) as $key => $value) {
            $content .= $key + 1 . ') ' . $value . '<br />';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title></title>
    <meta charset="utf-8">
</head>
<body>

<form method='post'>
    Write fake data to file:
    <input hidden name="fake_data_write">
    <input type='submit' value='Write'>
</form>
<?php
if (isset($content)) {
    echo $content;
}
?>
</body>
</html>
