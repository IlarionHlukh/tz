<?php
// start the session
session_start();

?>
<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/c/CodingLabYT-->
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>MovieSaver - портал для зберігання фільмів</title>
    <!-- CSS only -->
    <link href='https://cdnjs.cloudflare.com/ajax/libs/material-components-web/4.0.0/material-components-web.min.css' rel='stylesheet'>
    <link href='https://cdn.datatables.net/1.10.25/css/dataTables.material.min.css' rel='stylesheet'>
    <!-- Compiled and minified JavaScript -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="assets/js/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="sidebar">
    <div class="logo-details">
        <i class='bx bxl-c-plus-plus icon'></i>
        <div class="logo_name">MovieSaver</div>
        <i class='bx bx-menu' id="btn" ></i>
    </div>
    <ul class="nav-list">
        <li>
            <a href="index.php">
                <i class='bx bx-grid-alt'></i>
                <span class="links_name">Головна</span>
            </a>
            <span class="tooltip">Головна</span>
        </li>
        <li>
            <a href="films.php">
                <i class='bx bx-folder' ></i>
                <span class="links_name">Перелік фільмів</span>
            </a>
            <span class="tooltip">Перелік фільмів</span>
        </li>
        <li>
            <a href="#">
                <i class='bx bx-heart' ></i>
                <span class="links_name">Рейтинги IMDB</span>
            </a>
            <span class="tooltip">Рейтинги IMDB</span>
        </li>
        <?php
             if(!$_SESSION)
        {
        ?>
            <li class="profile">
                <div class="profile-details">
                    <img src="assets/img/profile.jpg" alt="profileImg">
                    <div class="name_job">
                        <a href="signin.php">Увійти</a>
                    </div>
                </div>
            </li>
        <?php
        }else{
        ?>
                 <li class="profile">
            <div class="profile-details">
                <img src="assets/img/profile.jpg" alt="profileImg">
                <div class="name_job">
                    <div class="name"><?php echo $_SESSION['username'] ?></div>
                </div>
            </div>
            <a href="logout.php">
            <i class='bx bx-log-out' id="log_out" ></i>
            </a>
        </li>
        <?php
             }
        ?>
    </ul>
    <a href="logout.php" class="nav__link">
        <i class='bx bx-log-out nav__icon' ></i>
        <span class="nav__name">Log Out</span>
    </a>
</div>