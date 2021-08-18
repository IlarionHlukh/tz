<?php
error_reporting(0);
include("layout.php");

if(!$_SESSION['userid'])
{

    header("Location: signin.php");
}


?>

<?php

require_once 'config.php';

if(isset($_GET['delete_id']) && empty($_GET['type_image']))
{
    $id = $_GET['delete_id'];
    $stmt_delete = $DB_con->prepare('DELETE FROM films WHERE id =:id');
    $stmt_delete->bindParam(':id',$_GET['delete_id']);
    $stmt_delete->execute();

}
if(isset($_GET['delete_id']) && !empty($_GET['type_image']))
{
    $id = $_GET['delete_id'];
    $stmt_select = $DB_con->prepare('SELECT image FROM films WHERE id =:id');
    $stmt_select->execute(array(':id' => $id));
    $imgRow = $stmt_select->fetch(PDO::FETCH_ASSOC);
    unlink("films_images/" . $imgRow['image']);


    $stmt_delete = $DB_con->prepare('DELETE FROM films WHERE id =:id');
    $stmt_delete->bindParam(':id',$_GET['delete_id']);
    $stmt_delete->execute();

}

?>
<section class="home-section">
    <div class="alert alert-info">
        <center> <h3><strong>Перелік фільмів</strong> </h3></center>
    </div>
    <div class="container">
        <a class="btn btn-info" id = "import" href="#openModal_import" role="button">Імпорт з файлу</a>
        <a class="btn btn-info" id = "btn_modal"  href="#openModal" role="button">Додати фільм</a>
    </div>
    <br />
    <br />
    <div class="table-responsive">
        <table id="example" class="mdl-data-table" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th data-orderable="false"></th>
                <th>Назва</th>
                <th>Рік</th>
                <th>Формат</th>
                <th>Перелік акторів</th>
                <th>Дата внесення</th>
                <th data-orderable="false">Дії</th>

            </tr>
            </thead>
            <tbody>
            <?php
            include("config.php");
            $stmt = $DB_con->prepare('SELECT * FROM films');
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    extract($row);
                    ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td>
                            <center>
                                <?php
                                if(empty($image))
                                {
                                ?>
                                    <img src="assets/img/default.jpg" class="img img-rounded"  width="90" height="60" />
                                <?php
                                }
                                else{
                                ?>
                                    <img src="films_images/<?php echo $image; ?>" class="img img-rounded"  width="90" height="60" /></center>
                            <?php
                                }
                                ?>
                        </td>
                        <td><?php echo $title; ?></td>
                        <td><?php echo $year; ?></td>
                        <td><?php echo $format; ?></td>
                        <td><?php echo $actors; ?></td>
                        <td><?php echo $date_created; ?></td>
                        <td>
                            <a class="btn btn-info"  href="editfilms.php?edit_id=<?php echo $row['id'];?>"><span class='glyphicon glyphicon-zoom-in'></span>Редагувати</a>
                            <a class="btn btn-danger" href="?delete_id=<?php echo $row['id'];?>&type_image=<?php echo $image;?> " title="click for delete" onclick="return confirm('Ви впевненні?')"><span class='glyphicon glyphicon-trash'></span>Видалити</a>
                            <a class="btn btn-success" href="filmdetails.php?show_id=<?php echo $row['id']; ?>" title="click for show"><span class='glyphicon glyphicon-zoom-in'></span>Переглянути</a>
                        </td>
                    </tr>

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
                        <span class="glyphicon glyphicon-info-sign"></span> &nbsp; Фільмів не знайдено ...
                    </div>
                </div>
                <?php
            }
            ?>
    </div>
</section>
<div id="openModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Добавити фільм</h3>
                <a href="#close" title="Close" class="close">×</a>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" method="post" action="addfilms.php">
                    <div id="prompt"></div>
                    <div class="field">
                        <input class="form-control" placeholder="Назва" name="title" type="text" required>
                    </div>
                    <div class="field">
                        <input id="year" class="select-css2" placeholder="Рік" name="year" type="number" min ="1850" max="2021" required>
                    </div>
                    <div class="field">
                        <select name="format" class="select-css"> <!--Supplement an id here instead of using 'name'-->
                            <option selected disabled>Формат</option>
                            <option value="VHS">VHS</option>
                            <option value="DVD">DVD</option>
                            <option value="Blu-Ray">Blu-Ray</option>
                        </select>
                    </div>
                    <div class="field">
                        <input id="actors" class="form-control" placeholder="Перелік акторів" name="actors" type="text" title= "Писати перелік акторів через кому! В іншому випадку система рахуватиме написане як одну людину." pattern="^(\w+[, ]+)*\w+$" required>
                    </div>
                    <p>Вибрати картинку</p>
                    <div class="field">
                        <input class="form-control"  type="file" name="image" accept="image/*" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn_mod" name="film_save">Додати</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="openModal_import" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Імпорт</h3>
                    <a href="#close" title="Close" class="close">×</a>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" method="post" action="import.php">
                        <p>Вибрати файл</p>
                        <div class="field">
                            <input class="form-control"  type="file" name="import"/>
                        </div>

                </div>
                <div class="modal-footer">
                    <button class="btn_import" name="import">Імпорт</button>
                    </form>
                </div>
            </div>
        </div>
</div>
<script src="assets/js/script.js"></script>
<script type="text/javascript" charset="utf-8">
        $.extend( $.fn.dataTableExt.oSort, {
            "uk-pre": function ( a ) {
                var special_letters = {
                    "А": "Aa",
                    "а": "aa",
                    "Б": "Ab",
                    "б": "ab",
                    "В": "Ca",
                    "в": "ca",
                    "Г": "Cb",
                    "г": "cb",
                    "Ґ": "Da",
                    "ґ": "da",
                    "Д": "Db",
                    "д": "db",
                    "Е": "Ea",
                    "е": "ea",
                    "Є": "Eb",
                    "є": "eb",
                    "Ж": "Ec",
                    "ж": "ec",
                    "З": "Ia",
                    "з": "ia",
                    "И": "Ib",
                    "и": "ib",
                    "І": "Na",
                    "і": "na",
                    "Ї": "Nb",
                    "ї": "nb",
                    "Й": "Oa",
                    "й": "oa",
                    "К": "Ra",
                    "к": "ra",
                    "Л": "Rb",
                    "л": "rb",
                    "М": "Sa",
                    "м": "sa",
                    "Н": "Sb",
                    "н": "sb",
                    "О": "Ta",
                    "о": "ta",
                    "П": "Tb",
                    "п": "tb",
                    "Р": "Ua",
                    "р": "ua",
                    "С": "Ub",
                    "с": "ub",
                    "Т": "Uc",
                    "т": "uc",
                    "У": "Uc",
                    "у": "Ya",
                    "Ф": "ya",
                    "ф": "Yb",
                    "Х": "yb",
                    "х": "Za",
                    "Ц": "za",
                    "ц": "Zb",
                    "Ч": "zb",
                    "ч": "zba",
                    "Ш": "zbc",
                    "ш": "zbd",
                    "Щ": "zbe",
                    "щ": "zca",
                    "Ю": "zcb",
                    "ю": "zcv",
                    "Я": "zcd",
                    "я": "zde"
                };
                for (var val in special_letters)
                    a = a.split(val).join(special_letters[val]).toLowerCase();
                return a;
            },

            "uk-asc": function ( a, b ) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            },

            "uk-desc": function ( a, b ) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            }

        } );

        $('#example').dataTable( {
            columnDefs: [
                { type: 'uk',
                  targets: ['_all'],
                 className: 'mdc-data-table__cell'
                }
            ]
        } );
</script>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.js"></script>
<script>
    $( document ).ready(function(){
        $( "#actors" ).change(function(){
            var str = document.getElementById('actors').value.trim();

            str2 = str.replace(/\s+/g, '');
            console.log(str2);
            var strArray = new Array();
            strArray = str2.split(",");
            findDuplicates = arr => arr.filter((item, index) => arr.indexOf(item) != index);
            var last = findDuplicates(strArray);
            if(last.length > 0){
                $('.btn_mod').attr('disabled','disabled');
                $('#prompt').removeClass().addClass('error').html('Присутні дубльовані записи!');
            }else{
                $('.btn_mod').removeAttr('disabled');
                $('#prompt').removeClass('error');
            }

        });
    });
</script>
</body>
</html>
