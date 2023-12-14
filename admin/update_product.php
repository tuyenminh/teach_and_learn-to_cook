<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $id_cate = $_POST['id_cate'];
   $id_cate = filter_var($id_cate, FILTER_SANITIZE_STRING);

   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);

   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);

   $opening_day = $_POST['opening_day'];
   $opening_day = filter_var($opening_day, FILTER_SANITIZE_STRING); 

   $study_time = $_POST['study_time'];
   $study_time = filter_var($study_time, FILTER_SANITIZE_STRING);


   $update_product = $conn->prepare("UPDATE `courses` SET name = ?,  id_cate = ?, price = ?, description = ?, opening_day = ?, study_time = ? WHERE id = ?");
   $update_product->execute([$name,  $id_cate, $price,  $description, $opening_day, $study_time, $pid, ]);

   $message[] = 'Cập nhật thành công!';

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'Kích thước hình ảnh quá lớn!';
      }else{
         $update_image = $conn->prepare("UPDATE `courses` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);
         move_uploaded_file($image_tmp_name, $image_folder);
         unlink('../uploaded_img/'.$old_image);
         $message[] = 'Hình ảnh đã được cập nhật!';
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
   <title>Cập nhật khóa học</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="h../css/admin.css">
   <link rel="stylesheet" href="ckeditor_4.21.0_full/ckeditor/contents.css">
  <link rel="stylesheet" href="ckeditor_4.21.0_full/ckeditor/ckeditor.css">
  <script src="ckeditor_4.21.0_full/ckeditor/ckeditor.js"></script>
  <script src="ckeditor_4.21.0_full/ckeditor/config.js"></script>

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- update product section starts  -->
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
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

<section class="update-product">

   <h1 class="heading">Cập nhật khóa học</h1>

   <?php
      $update_id = $_GET['update'];
      $show_products = $conn->prepare("SELECT * FROM `courses` WHERE id = ?");
      $show_products->execute([$update_id]);
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <span>Tên khóa học</span>
      <input type="text" required placeholder="Nhập tên khóa học" name="name" maxlength="100" class="box" value="<?= $fetch_products['name']; ?>">
      <span>Giá</span>
      <input type="number" min="0" max="9999999999" required placeholder="Giá" name="price" onkeypress="if(this.value.length == 10) return false;" class="box" value="<?= $fetch_products['price']; ?>">
      <span>Danh mục</span>
      <select name="id_cate" class="box" required>
    <?php
    $select_courses = $conn->prepare("SELECT * FROM `category`");
    $select_courses->execute();
    
    $selected_id_cate = ''; // Khởi tạo biến để lưu id_cate của đối tượng đã được chọn.

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_cate"])) {
        $selected_id_cate = $_POST["id_cate"]; // Lấy giá trị đã được chọn từ biểu mẫu nếu có.
    }

    while ($fetch_courses = $select_courses->fetch(PDO::FETCH_ASSOC)) {  
        $id_cate = $fetch_courses['id_cate'];
        $name_cate = $fetch_courses['name_cate'];
        
        // Kiểm tra nếu id_cate của dòng dữ liệu trùng với id_cate đã được chọn.
        $selected = ($id_cate == $selected_id_cate) ? 'selected' : '';
        echo "<option value='$id_cate' $selected>$name_cate</option>";
    }
    ?>
</select>



      <span>Cập nhật hình ảnh</span>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <!-- <span>Cập nhật video</span>
      <input type="file" name="video" class="box" accept="video/mp3, video/mp4"> -->
      <span>Mô tả</span>
      
      <textarea name="description" id="post_content"> <?= htmlspecialchars_decode($fetch_products['description']); ?></textarea>
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
            filebrowserImageBrowseUrl : '../admin/ckfinder/ckfinder.html?Type=Images',
		      filebrowserFlashBrowseUrl : '../admin/ckfinder/ckfinder.html?Type=Flash',
		      filebrowserImageUploadUrl : '../admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		      filebrowserFlashUploadUrl : '../admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
	      });
      </script> -->

      <span>Ngày khai giảng</span>
      <input type="date" required placeholder="Nhập ngày khai giảng" name="opening_day" maxlength="100" class="box" value="<?= $fetch_products['opening_day']; ?>">
      <span>Thời gian học</span>
      <input type="text" required placeholder="Nhập thời gian học" name="study_time" maxlength="100" class="box" value="<?= $fetch_products['study_time']; ?>">
      <div class="flex-btn">
         <input type="submit" value="Cập nhật" class="btn" name="update">
         <a href="products.php" class="option-btn">Trở về</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">Khóa học trống!</p>';
      }
   ?>

</section>
   </div>
<!-- update product section ends -->






<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-table.js"></script>
<script src="https://localhost/food_website_backend/admin/ckeditor/ckeditor.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->



</body>
</html>