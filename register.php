<?php

require_once "session.php";
include("db_conection.php");


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $firstname = trim($_POST['firstname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    if($query = $db->prepare("SELECT * FROM users WHERE email = ?")) {
        $error = '';
        $query->bind_param('s', $email);
        $query->execute();

        $query->store_result();
        if ($query->num_rows > 0) {
            $error .= '<p class="error">Даний користувач вже зареєстрований!<a href="signin.php">Увійти!</a></p>';
        } else {

            if (strlen($password ) < 6) {
                $error .= '<p class="error">Довжина пароля не менше 6 символів.</p>';
            }

            if (empty($error) ) {
                $insertQuery = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?);");
                $insertQuery->bind_param("sss", $firstname,  $email, $password_hash);
                $result = $insertQuery->execute();
                if ($result) {
                    $success = '<p class="success">Реєстрація успішна! <a href="signin.php">Увійти!</a></p>';
                } else {
                    $error .= '<p class="error">Уппс! Щось пішло не так!</p>';
                }

            }
            $insertQuery->close();
        }
    }
    $query->close();
    // Close DB connection
    mysqli_close($db);
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Реєстрація</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="sigin">
<div class="center">
    <h1>Реєстрація</h1>
    <?php
    if(!empty($success)){
        echo '<div class="alert alert-success">' . $success . '</div>';
    }
    if(!empty($error)){
        echo '<div class="alert alert-danger">' . $error . '</div>';
    }
    ?>
    <form action="" method="post">
        <div class="txt_field">
            <input type="text" name="firstname" required>
            <span></span>
            <label>Імя</label>
        </div>
        <div class="txt_field">
            <input type="email" name="email" required>
            <span></span>
            <label>Email</label>
        </div>
        <div class="txt_field">
            <input type="password" name="password"required>
            <span></span>
            <label>Пароль</label>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Зареєструватися">
        </div>
    </form>
</div>
</body>
</html>

