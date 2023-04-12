<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Chi tiết công thức</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view-recipe">

   <h1 class="title">Chi tiết công thức</h1>

   <?php
      $pid = $_GET['pid'];
      $select_products = $conn->prepare("SELECT * FROM `recipe` WHERE id = ?");
      $select_products->execute([$pid]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
      <input type="hidden" name="making" value="<?= $fetch_products['making']; ?>">
      <div class="name"><h1 style="font-size:2vw;   margin-bottom: 20px;"><?= $fetch_products['name']; ?></h1></div>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" width="768" height="432"  style = "display: block; margin-left: auto; margin-right: auto;" alt="">
      <!-- <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a> -->
      <div class="flex">
         <!-- <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2"> -->
      </div>
   </form>
   <h1 style="font-size:2vw">Video hướng dẫn</h1>
   <iframe  style = "display: block; margin-left: auto; margin-right: auto; margin-bottom: 20px;"  width="768" height="432" src="<?= $fetch_products['video']; ?>" frameborder="0" allowfullscreen></iframe>
   
   <h1 style="font-size:2vw">Cách làm</h1>
   <div class="making" ><h1><?= $fetch_products['making']; ?> </h1></div>

   <?php
         }
      }else{
         echo '<p class="empty">Không có khóa học nào!</p>';
      }
   ?>

</section>
















<?php include 'components/footer.php'; ?>


<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>


</body>
</html>