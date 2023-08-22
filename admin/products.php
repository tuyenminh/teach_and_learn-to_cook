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
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   // $video = $_FILES['video']['name'];
   // $video = filter_var($image, FILTER_SANITIZE_STRING);
   // $video_tmp_name = $_FILES['video']['tmp_name'];
   // $video_folder = '../uploaded_video/'.$video;

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

         $insert_product = $conn->prepare("INSERT INTO `courses`(name, category, price, image, description, opening_day, study_time ) VALUES(?,?,?,?,?,?,?)");
         $insert_product->execute([$name, $category, $price, $image, $description, $opening_day, $study_time ]);

         $message[] = 'Thêm khóa học mới thành công!';
      }

   }


}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `courses` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   $delete_product = $conn->prepare("DELETE FROM `courses` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:products.php');

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
   <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
	<script type = "text/javascript" src= "ckeditor_4.21.0_full/ckeditor/ckeditor.js"></script>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin.css">
   <!-- <script src="js/jquery-1.11.1.min.js"></script>
   <script src="js/bootstrap.min.js"></script> -->



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

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Thêm khóa học</h3>
      
      <input type="text" required placeholder="Nhập tên khóa học" name="name" maxlength="100" class="box">
      <input type="number" min="0" max="9999999999" required placeholder="Nhập giá khóa học" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
      <select name="category" class="box" required>
         <option value="" disabled selected>Chọn danh mục --</option>
         <option value="Gia đình">Gia đình</option>
         <option value="Tiệc">Tiệc</option>
         <option value="Đồ uống">Đồ uống</option>
         <option value="Ăn vặt">Ăn vặt</option>
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
      <!-- <script type="text/javascript">
	      var editor = CKEDITOR.replace('post_content',{
            language:'vi',
            filebrowserImageBrowseUrl : '../admin/qt64_admin/templates/js/plugin/ckfinder/ckfinder.html?Type=Images',
		      filebrowserFlashBrowseUrl : '../admin/qt64_admin/templates/js/plugin/ckfinder/ckfinder.html?Type=Flash',
		      filebrowserImageUploadUrl : '../admin/qt64_admin/templates/js/plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		      filebrowserFlashUploadUrl : '../admin/qt64_admin/templates/js/plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
	      });
      </script> -->

      <input type="date" required placeholder="Nhập ngày khai giảng" name="opening_day" maxlength="100" class="box">
      <input type="text" required placeholder="Nhập thời gian học" name="study_time" maxlength="100" class="box">
      <input type="submit" value="Thêm khóa học" name="add_product" class="btn">
   </form>
</section>

<!-- add products section ends -->

<!-- show products section starts  -->

<section class="show-products" style="padding-top: 0;">

   <div class="box-container">

   <?php
      $show_products = $conn->prepare("SELECT * FROM `courses`");
      $show_products->execute();
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="flex">
         <div class="price" ><span></span><?= number_format($fetch_products['price']) . " VNĐ";  ?><span></span></div>
         <!-- currency_format() -->
         <div class="category"><?= $fetch_products['category']; ?></div>
      </div>
      <div class="name"><?= $fetch_products['name']; ?></div>
      <!-- <div class="description"><?= $fetch_products['description']; ?></div> -->

      <div class="flex-btn">
         <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Cập nhật</a>
         <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Xóa khóa học này?');">Xóa</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Không có khóa học nào!</p>';
      }
   ?>

   </div>

</section>
</div>
<!-- show products section ends -->










<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>
<!-- <script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-table.js"></script> -->
<!-- <script src="https://localhost/food_website_backend/admin/ckeditor/ckeditor.js"></script> -->


</body>
</html>