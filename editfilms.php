<?php
include("layout.php");

?>
<?php

	error_reporting(0);
	
	require_once 'config.php';
	
	if(isset($_GET['edit_id']) && !empty($_GET['edit_id']))
	{
		$id = $_GET['edit_id'];
		$stmt_edit = $DB_con->prepare('SELECT * FROM films WHERE id =:id');
		$stmt_edit->execute(array(':id'=>$id));
		$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
		extract($edit_row);
	}
	else
	{
		header("Location: films.php");
	}
	
	
	
	if(isset($_POST['film_save']))
	{
        $title = $_POST['title'];
        $year = $_POST['year'];
        $format = $_POST['format'];
        $actors = $_POST['actors'];
		

        $imgFile  = $_FILES['image_edit']['name'];
        $imgSize = $_FILES['image_edit']['size'];
        $tmp_dir = $_FILES['image_edit']['tmp_name'];

		if($imgFile)
		{
			$upload_dir = 'films_images/'; // upload directory
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
			$itempic = rand(1000,1000000).".".$imgExt;
			if(in_array($imgExt, $valid_extensions))
			{			
				if($imgSize < 5000000)
				{
					unlink($upload_dir.$edit_row['image']);
					move_uploaded_file($tmp_dir,$upload_dir.$itempic);
				}
				else
				{
					$errMSG = "Файл занадто великий!. Не більше 5мб";
					echo "<script>alert('Файл занадто великий!. Не більше 5мб')</script>";
					 
				}
			}
			else
			{
				$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";	
              echo "<script>alert('Помилка, тільки формати JPG, JPEG, PNG & GIF.')</script>";
			}	
		}
		else
		{
		
			$itempic = $edit_row['image'];
		}	
						
		

		if(!isset($errMSG))
		{
			$stmt = $DB_con->prepare('UPDATE films
									     SET title=:title, 
											 year=:year, 
									         format=:format, 
									         actors=:actors, 
										     image=:image 
								       WHERE id=:id');
			$stmt->bindParam(':title',$title);
			$stmt->bindParam(':year',$year);
            $stmt->bindParam(':format',$format);
            $stmt->bindParam(':actors',$actors);
			$stmt->bindParam(':image',$itempic);
			$stmt->bindParam(':id',$id);
				
			if($stmt->execute()){
				?>
                <script>
				alert('Успішно оновлено...');
                window.location.href='films.php';
				</script>
                <?php
			}
			else{
				$errMSG = "Помилка, не оновлено!";
				 echo "<script>alert('Помилка, не оновлено!')</script>";
			}
		
		}
		
						
	}
	
?>
<section class="home-section">
<form method="post" enctype="multipart/form-data" class="form-horizontal">

			 <div class="alert alert-info">
                          <center> <h3><strong>Оновити фільм</strong> </h3></center>
						  
						  </div>
    <form enctype="multipart/form-data" method="post">
        <div class="field">
            <input class="form-control" placeholder="Назва" name="title" type="text" value="<?php echo $title; ?>"required>
        </div>
        <div class="field">
            <input id="year" class="form-control" placeholder="Рік" name="year" type="text" value="<?php echo $year; ?>"required>
        </div>
        <div class="field">
            <input id="format" class="form-control" placeholder="Формат" name="format" type="text" value="<?php echo $format; ?>"required>
        </div>
        <div class="field">
            <input id="actors" class="form-control" placeholder="Перелік акторів" name="actors" type="text" value="<?php echo $actors; ?>" required>
        </div>
        <p>Вибрати картинку</p>
        <div class="field">
            <input class="form-control"  type="file" name="image_edit" accept="image/*"/>
        </div>
        <div class="modal-footer">
            <button class="btn_mod" name="film_save">Оновити</button>
    </form>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>
</section>
</html>
