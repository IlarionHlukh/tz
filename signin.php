<?php

include("db_conection.php");
require_once "session.php";


$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // validate if email is empty
    if (empty($email)) {
        $_SESSION['error']="Уведіть email!!";
    }

    // validate if password is empty
    if (empty($password)) {
        $_SESSION['error']="Уведіть пароль.";

    }

    // Validate credentials
    if(!empty($email) && !empty($password)){
        // Prepare a select statement
        $sql = "SELECT id, name, password FROM users WHERE email = ?";

        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $email;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id,$name, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["userid"] = $id;
                            $_SESSION["username"] = $name;

                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $_SESSION['error']= "Неправильний логін або пароль!";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $_SESSION['error']= "Неправильний логін або пароль!";
                }
            } else{
                echo "Уппс. Щось пішло не так. Спробуйе ще раз!";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($db);
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Animated Login Form | CodingNepal</title>
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body class="sigin">
    <div class="center">
      <h1>Вхід</h1>
        <?php
        if(isset($_SESSION['error']))
            {
            ?>
            <div class="alert alert-danger fade in">
                <strong>Помилка!</strong> <?php echo $_SESSION['error']; ?>
            </div>
            <?php
            }
            unset($_SESSION['error']);
        ?>
        <form action="" method="post">
            <div class="txt_field">
                <input type="email" name="email" required>
                <span></span>
                <label>Email</label>
            </div>
            <div class="txt_field">
                <input type="password" name="password"required>
                <span></span>
                <label>Password</label>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-primary" value="Увійти">
            </div>
        <div class="signup_link">
            Ще не зареєстровані?<a href="register.php">Зареєструватися</a>
        </div>
      </form>
    </div>

  </body>
</html>

