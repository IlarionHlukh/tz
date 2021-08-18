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

    $extensions = array('txt');

    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "Тільки формат txt!";
        echo "<script>alert('Тільки формат txt!')</script>";
        echo"<script>window.open('films.php','_self')</script>";
        exit();
    }

    if ($file_size > 2097152) {
        $errors[] = 'Файл занадто великий!. Не більше 2мб';
        echo "<script>alert('Файл занадто великий!. Не більше 2мб')</script>";
        echo"<script>window.open('films.php','_self')</script>";
        exit();
    }

    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, "files/" . $file_name);
        echo "<script>alert('Успішно внесено!')</script>";
        echo"<script>window.open('films.php','_self')</script>";
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
