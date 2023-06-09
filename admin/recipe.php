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

   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $video = $_POST['video'];
   $video = filter_var($video, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   $making = $_POST['making'];
   $making = filter_var($making, FILTER_SANITIZE_STRING);

   $time = $_POST['time'];
   $time = filter_var($time, FILTER_SANITIZE_STRING); 

   $select_recipes = $conn->prepare("SELECT * FROM `recipe` WHERE name = ?");
   $select_recipes->execute([$name]);

   if($select_recipes->rowCount() > 0){
      $message[] = 'Công thức đã tồn tại!';
   }else{
      if($image_size > 2000000){
         $message[] = 'Kích thước ảnh không thích hợp';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);

         $insert_recipe = $conn->prepare("INSERT INTO `recipe`(name, category, image, making, time, video ) VALUES(?,?,?,?,?,?)");
         $insert_recipe->execute([$name, $category, $image, $making, $video, $time, ]);

         $message[] = 'Thêm công thức mới thành công!';
      }

   }


}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_recipe_image = $conn->prepare("SELECT * FROM `recipe` WHERE id = ?");
   $delete_recipe_image->execute([$delete_id]);
   $fetch_delete_image = $delete_recipe_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   $delete_recipe = $conn->prepare("DELETE FROM `recipe` WHERE id = ?");
   $delete_recipe->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:recipe.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Công thức</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
	<script type = "text/javascript" src= "ckeditor_4.21.0_full/ckeditor/ckeditor.js"></script>


</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- add products section starts  -->

<section class="add-products">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Thêm công thức</h3>
      <input type="text" required placeholder="Nhập tên món" name="name" maxlength="100" class="box">
      <select name="category" class="box" required>
         <option value="" disabled selected>Chọn danh mục --</option>
         <option value="Gia đình">Gia đình</option>
         <option value="Tiệc">Tiệc</option>
         <option value="Đồ uống">Đồ uống</option>
         <option value="Ăn vặt">Ăn vặt</option>
      </select>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <textarea name="ingre" id="post_content" class="box"></textarea>
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
      <textarea name="making" id="post_content1" class="box"></textarea>
      <script>
         // Thay thế <textarea id="post_content"> với CKEditor
         CKEDITOR.replace( 'post_content1', {
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
      <input type="text" required placeholder="Nhập link video" name="time" maxlength="100" class="box">
      <input type="text" required placeholder="Nhập thời gian nấu" name="video" maxlength="100" class="box">
      <input type="submit" value="Thêm công thức" name="add_product" class="btn">
   </form>

</section>

<!-- add products section ends -->

<!-- show products section starts  -->

<section class="show-products" style="padding-top: 0;">

   <div class="box-container">

   <?php
      $show_recipe = $conn->prepare("SELECT * FROM `recipe`");
      $show_recipe->execute();
      if($show_recipe->rowCount() > 0){
         while($fetch_recipe = $show_recipe->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_recipe['image']; ?>" alt="">
      <div class="flex">
         <!-- currency_format() -->
         <div class="category"><?= $fetch_recipe['category']; ?></div>
      </div>
      <div class="name"><?= $fetch_recipe['name']; ?></div>
      <div class="flex-btn">
         <a href="update_recipe.php?update=<?= $fetch_recipe['id']; ?>" class="option-btn">Cập nhật</a>
         <a href="recipe.php?delete=<?= $fetch_recipe['id']; ?>" class="delete-btn" onclick="return confirm('Xóa công thức này?');">Xóa</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Không có công thức nào!</p>';
      }
   ?>

   </div>

</section>

<!-- show products section ends -->










<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>
</body>
</html>