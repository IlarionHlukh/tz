<?php


include("layout.php");

if(!$_SESSION['userid'])
{

    header("Location: signin.php");
}

?>

<section class="home-section">
    <div class="alert alert-info">
        <center> <h3><strong>Інформація про фільм</strong> </h3></center>
    </div>
    <div class="container">
        <a class="btn btn-info" id = "import" href="#openModal_import" role="button">Імпорт з файлу</a>
        <a class="btn btn-info" id = "btn_modal"  href="#openModal" role="button">Добавити фільм</a>
    </div>
    <br />
    <br />
    <div class="table-responsive">
            <?php
            include("config.php");
            if(isset($_GET['show_id'])) {
                $id = $_GET['show_id'];
                $stmt = $DB_con->prepare('SELECT * FROM films where id = :id');
                $stmt->execute(array(':id' => $id));
                $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    extract($row);
                    ?>
                    <div class="box2">
                        <?php
                        if($image === null)
                        {
                            ?>
                            <img src="assets/img/default.jpg" class="img img-rounded"  width="500" height="300" />
                            <?php
                        }
                        else{
                            ?>
                            <img src="films_images/<?php echo $image; ?>" class="img img-rounded"  width="500" height="300" /></center>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="box2">
                        <div class="row">
                            <strong>Назва:</strong> <?php echo $title; ?>
                        </div>
                        <div class="row">
                            <strong>Рік випуску:</strong> <?php echo $year; ?>
                        </div>
                        <div class="row">
                            <strong>Формат:</strong> <?php echo $format; ?>
                        </div>
                        <div class="row">
                            <strong>В головних ролях:</strong> <?php echo $actors; ?>
                        </div>
                        </div>
                    </div>
                        <div class="row">
                        <div class="col-md-4">

                                <span class='glyphicon glyphicon-zoom-in'>Опис фільму</span>
                        </div>
                        </div>
                    <div class="col-md-8">


                    </div>
                        <td>
                            <a class="btn btn-info"  href="editfilms.php?edit_id=<?php echo $row['id'];?>"><span class='glyphicon glyphicon-zoom-in'></span>Редагувати</a>
                            <a class="btn btn-danger" href="films.php?delete_id=<?php echo $row['id'];?>&type_image=<?php echo $image;?> " title="click for delete" onclick="return confirm('Are you sure to remove this item?')"><span class='glyphicon glyphicon-trash'></span>Видалити</a>
                        </td>
                    <?php
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "<br />";
                echo '<div class="alert alert-default">
                    </div>
	</div>';

                echo "</div>";
            }
            else
            {
                ?>
                <div class="col-xs-12">
                    <div class="alert alert-warning">
                        <span class="glyphicon glyphicon-info-sign"></span>
                    </div>
                </div>
                <?php
            }
                    }
            ?>
    </div>
</section>
<script src="assets/js/script.js"></script>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>

