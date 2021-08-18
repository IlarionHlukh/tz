<?php
session_start();

if(!$_SESSION['userid'])
{

    header("Location: signin.php");//redirect to login page to secure the welcome page without login access.
}

?>

<?php
error_reporting(0);
include("db_conection.php");

if(isset($_POST['film_save']) && !empty(trim($_POST['title'])))
{
$title = htmlspecialchars(trim($_POST['title']));
$year = htmlspecialchars($_POST['year']);
$format = htmlspecialchars($_POST['format']);
$actors = htmlspecialchars($_POST['actors']);

$stmt = $db->prepare("select * from films WHERE title='$title'");
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0)
    {
echo "<script>alert('Даний фільм вже добавлено. Спробуйте добавити новий фільм!')</script>";
 echo"<script>window.open('films.php','_self')</script>";
exit();
    }
 
$imgFile = $_FILES['image']['name'];
$tmp_dir = $_FILES['image']['tmp_name'];
$imgSize = $_FILES['image']['size'];

$upload_dir = 'films_images/';
$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); 
$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); 
$itempic = rand(1000,1000000).".".$imgExt;



		if (in_array($imgExt, $valid_extensions)) {

			if ($imgSize < 5000000) {
				move_uploaded_file($tmp_dir, $upload_dir . $itempic);
				$saveitem = "insert into films (title,year,format,actors, image, date_created) VALUE ('$title','$year','$format','$actors','$itempic',CURDATE())";
				mysqli_query($db, $saveitem);
				echo "<script>alert('Фільм успішно додано!')</script>";
				echo "<script>window.open('films.php','_self')</script>";
			} else {

				echo "<script>alert('Файл занадто великий!. Не більше 5мб')</script>";
				echo "<script>window.open('films.php','_self')</script>";
			}
		} else {

			echo "<script>alert('Помилка, тільки формати JPG, JPEG, PNG & GIF.')</script>";
			echo "<script>window.open('films.php','_self')</script>";

		}


}
echo "<script>alert('Помилка, перевірте коректність даних!')</script>";
echo "<script>window.open('films.php','_self')</script>";
?>









