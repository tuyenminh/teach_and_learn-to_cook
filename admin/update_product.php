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

   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);

   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);

   $opening_day = $_POST['opening_day'];
   $opening_day = filter_var($opening_day, FILTER_SANITIZE_STRING); 

   $study_time = $_POST['study_time'];
   $study_time = filter_var($study_time, FILTER_SANITIZE_STRING);


   $update_product = $conn->prepare("UPDATE `courses` SET name = ?, category = ?, price = ?, description = ?, opening_day = ?, study_time = ?  WHERE id = ?");
   $update_product->execute([$name, $category, $price,  $description, $opening_day, $study_time, $pid, ]);

   $message[] = 'Cập nhật thành công!';

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   // $old_video = $_POST['old_video'];
   // $video = $_FILES['video']['name'];
   // $video = filter_var($image, FILTER_SANITIZE_STRING);
   // $video_tmp_name = $_FILES['video']['tmp_name'];
   // $video_folder = '../uploaded_video/'.$video;

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
   // if(!empty($video)){
   //    $update_video = $conn->prepare("UPDATE `courses` SET video = ? WHERE id = ?");
   //       $update_video->execute([$video, $pid]);
   //       move_uploaded_file($video_tmp_name, $video_folder);
   //       unlink('../uploaded_video/'.$old_video);
   //       $message[] = 'Video đã được cập nhật!';
   // }

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
   <link rel="stylesheet" href="../css/admin_style.css">
   <script type = "text/javascript" src= "ckeditor/ckeditor.js"></script>


</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- update product section starts  -->

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
      <!-- <input type="hidden" name="old_video" value="<?= $fetch_products['video']; ?>">
      <img src="../uploaded_video/<?= $fetch_products['video']; ?>" alt=""> -->
      <span>Tên khóa học</span>
      <input type="text" required placeholder="Nhập tên khóa học" name="name" maxlength="100" class="box" value="<?= $fetch_products['name']; ?>">
      <span>Giá</span>
      <input type="number" min="0" max="9999999999" required placeholder="Giá" name="price" onkeypress="if(this.value.length == 10) return false;" class="box" value="<?= $fetch_products['price']; ?>">
      <span>Danh mục</span>
      <select name="category" class="box" required>
         <option selected value="<?= $fetch_products['category']; ?>"><?= $fetch_products['category']; ?></option>
         <option value="Gia đình">Gia đình</option>
         <option value="Tiệc">Tiệc</option>
         <option value="Đồ uống">Đồ uống</option>
         <option value="Ăn vặt">Ăn vặt</option>
      </select>
      <span>Cập nhật hình ảnh</span>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <!-- <span>Cập nhật video</span>
      <input type="file" name="video" class="box" accept="video/mp3, video/mp4"> -->
      <span>Mô tả</span>
      <textarea type="text" required placeholder="Nhập mô tả" id="post_content" name="description" class="box" value="<?= $fetch_products['description']; ?>"></textarea>
      <span>Ngày khai giảng</span>
      <input type="text" required placeholder="Nhập ngày khai giảng" name="opening_day" maxlength="100" class="box" value="<?= $fetch_products['opening_day']; ?>">
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

<!-- update product section ends -->










<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>
<script src="https://localhost/food_website_backend/admin/ckeditor/ckeditor.js"></script>
<script>
    // Thay thế <textarea id="post_content"> với CKEditor
    CKEDITOR.replace( 'post_content' );// tham số là biến name của textarea

</script>
</body>
</html>