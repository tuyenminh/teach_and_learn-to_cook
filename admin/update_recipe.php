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

   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $video = $_POST['video'];
   $video = filter_var($video, FILTER_SANITIZE_STRING);

   $making = $_POST['making'];
   $making = filter_var($making, FILTER_SANITIZE_STRING);

   $time = $_POST['time'];
   $time = filter_var($time, FILTER_SANITIZE_STRING); 

   $update_recipe = $conn->prepare("UPDATE `recipe` SET name = ?, category = ?, making = ?, time = ?, video = ?  WHERE id = ?");
   $update_recipe->execute([$name, $category, $making, $time, $video, $pid, ]);

   $message[] = 'Cập nhật thành công!';

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

//    $old_video = $_POST['old_video'];
//    $video = $_FILES['video']['name'];
//    $video = filter_var($image, FILTER_SANITIZE_STRING);
//    $video_tmp_name = $_FILES['video']['tmp_name'];
//    $video_folder = '../uploaded_video/'.$video;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'Kích thước hình ảnh quá lớn!';
      }else{
         $update_image = $conn->prepare("UPDATE `recipe` SET image = ? WHERE id = ?");
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
   <title>Cập nhật công thức</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
	<script type = "text/javascript" src= "ckeditor_4.21.0_full/ckeditor/ckeditor.js"></script>


</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- update product section starts  -->

<section class="update-product">

   <h1 class="heading">Cập nhật công thức</h1>

   <?php
      $update_id = $_GET['update'];
      $show_recipe = $conn->prepare("SELECT * FROM `recipe` WHERE id = ?");
      $show_recipe->execute([$update_id]);
      if($show_recipe->rowCount() > 0){
         while($fetch_recipe = $show_recipe->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_recipe['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_recipe['image']; ?>">
      <img src="../uploaded_img/<?= $fetch_recipe['image']; ?>" alt="">
      <span>Tên món</span>
      <input type="text" required placeholder="Nhập tên món" name="name" maxlength="100" class="box" value="<?= $fetch_recipe['name']; ?>">
      <span>Danh mục</span>
      <select name="category" class="box" required>
         <option selected value="<?= $fetch_recipe['category']; ?>"><?= $fetch_recipe['category']; ?></option>
         <option value="Gia đình">Gia đình</option>
         <option value="Tiệc">Tiệc</option>
         <option value="Đồ uống">Đồ uống</option>
         <option value="Ăn vặt">Ăn vặt</option>
      </select>
      <span>Cập nhật hình ảnh</span>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <span>Nguyên liệu</span>
      <textarea type="text" id="post_content" name="ingre" class="form-control"><?= $fetch_recipe['ingre']; ?></textarea>
      <script>
         // Thay thế <textarea id="post_content"> với CKEditor
         CKEDITOR.replace( 'post_content',{
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
      <span>Cách làm</span>
      <textarea type="text" id="post_content1" name="making" class="form-control" ><?= $fetch_recipe['making']; ?></textarea>
      <script>
         // Thay thế <textarea id="post_content"> với CKEditor
         CKEDITOR.replace( 'post_content1',{
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
      <span>Thời gian nấu</span>
      <input type="text" required placeholder="Nhập thời gian nấu " name="time" maxlength="100" class="box" value="<?= $fetch_recipe['time']; ?>">
      <span>Link video</span>
      <input type="text" required placeholder="Nhập link video" name="video" maxlength="100" class="box" value="<?= $fetch_recipe['video']; ?>">
      <div class="flex-btn">
         <input type="submit" value="Cập nhật" class="btn" name="update">
         <a href="recipe.php" class="option-btn">Trở về</a>
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

</body>
</html>