<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   
   $id_cate = $_POST['id_cate'];
   $id_cate = filter_var($id_cate, FILTER_SANITIZE_STRING);

   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);


   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;


   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);

   $opening_day = $_POST['opening_day'];
   $opening_day = filter_var($opening_day, FILTER_SANITIZE_STRING); 

   $study_time = $_POST['study_time'];
   $study_time = filter_var($study_time, FILTER_SANITIZE_STRING);

   $select_products = $conn->prepare("SELECT * FROM `courses` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Khóa học đã tồn tại!';
   }else{
      if($image_size > 2000000){
         $message[] = 'Kích thước ảnh không thích hợp';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);
         // move_uploaded_file($video_tmp_name, $video_folder);

         $insert_product = $conn->prepare("INSERT INTO `courses`(name, price, image, description, opening_day, study_time,  id_cate ) VALUES(?,?,?,?,?,?,?)");
         $insert_product->execute([$name, $price, $image, $description, $opening_day, $study_time, $id_cate]);

         $message[] = 'Thêm khóa học mới thành công!';
      }

   }


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Khóa học</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- link boostrap -->
	<script type = "text/javascript" src= "ckeditor_4.21.0_full/ckeditor/ckeditor.js"></script>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- add products section starts  -->
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
<section class="add-products">
   <?php include '../components/sidebar.php' ?>
   <div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home">
						<use xlink:href="#stroked-home"></use>
					</svg></a></li>
			<li class="active">Trang Khóa học</li>
		</ol>
	</div>
   <?php include '../components/message.php' ?>

   <form action="" method="POST" enctype="multipart/form-data" style = "border: var(--border);">
      <h3>Thêm khóa học</h3>
      
      <input type="text" required placeholder="Nhập tên khóa học" name="name" maxlength="100" class="box">
      <input type="number" min="0" max="9999999999" required placeholder="Nhập giá khóa học" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
      <select name="id_cate" class="box" required>
         <option value="" disabled selected>Chọn danh mục --</option>

    <?php
                                // $query=mysqli_query($conn, "SELECT*FROM category ORDER BY cat_id ASC");
                                $select_courses = $conn->prepare("SELECT * FROM `category` ORDER BY id_cate ASC");
                                $select_courses->execute();
                                while($fetch_courses = $select_courses->fetch(PDO::FETCH_ASSOC)){ ?>
                                    <option value=<?php echo $fetch_courses['id_cate'] ?>><?php echo $fetch_courses['name_cate']; ?></option>
                                <?php } ?>
      </select>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <textarea name="description" id="post_content" class="box"></textarea>
      <script>
         // Thay thế <textarea id="post_content"> với CKEditor
         CKEDITOR.replace( 'post_content', {
  // Khai báo encoding cho CKEditor
  entities: false,
  basicEntities: false,
  entities_greek: false,
  entities_latin: false,
  entities_additional: '',
  entities: '',
  encoding: 'utf-8',
  entities_processNumerical: true,
  entities_apos: true
});      </script>
      <input type="date" required placeholder="Nhập ngày khai giảng" name="opening_day" maxlength="100" class="box">
      <input type="text" required placeholder="Nhập thời gian học" name="study_time" maxlength="100" class="box">
      <input type="submit" value="Thêm khóa học" name="add_product" class="btn">
      <a href="products.php" class="option-btn">Trở về</a>

   </form>
</section>
</div>

</body>
</html>