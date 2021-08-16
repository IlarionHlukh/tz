<?php

include("db_conection.php");

error_reporting(0);

if (isset($_FILES['import'])) {
    $errors = array();
    $file_name = $_FILES['import']['name'];
    $file_size = $_FILES['import']['size'];
    $file_tmp = $_FILES['import']['tmp_name'];
    $file_type = $_FILES['import']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['import']['name'])));

    $extensions = array('txt', 'xls', 'doc');

    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
    }

    if ($file_size > 2097152) {
        $errors[] = 'File size must be excately 2 MB';
    }

    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, "files/" . $file_name);
        echo "Успішно внесено!";
        header("Location: films.php");
    } else {
        print_r($errors);
    }
}

$keys_array = ['TITLE', 'YEAR', 'FORMAT', 'ACTORS'];

$sql_insert_base = [];

$array = file("files/". $file_name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);


array_shift($array);

foreach($array as $line) {
    $rows  = explode(":", $line);
    $next[] = $rows[1];
}

foreach (array_chunk($next, 4, true) as $hone) {
    $values_array = array_combine($keys_array, $hone);
    $sql_insert_base[] = '(\'' . trim($values_array['TITLE']) . '\', ' . trim($values_array['YEAR']) .
        ', \'' . trim($values_array['FORMAT']) . '\', \'' . trim($values_array['ACTORS']) . '\', CURDATE())';
}


$sql_txt= 'INSERT INTO films (' . implode(', ', $keys_array) . ', date_created) VALUES ' . implode(', ', $sql_insert_base) . ';';


mysqli_query($db,$sql_txt);
