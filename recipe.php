<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Công thức</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>Công thức</h3>
   <p><a href="home.php">Trang chủ</a> <span> / Công thức</span></p>
</div>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>

      <div class="content">
         <h3>Chuyên mục công thức nấu ăn</h3>
         <p>Chuyên mục Công thức nấu ăn cơ bản của Cooking Food sẽ là cuốn sổ tay dạy nấu ăn bổ ích, đồng hành cùng người nội trợ để mang đến những món ăn thơm ngon, bổ dưỡng cho bữa ăn gia đình và người thân cũng như giúp người Đầu bếp góp phần nâng cao tay nghề, khám phá thêm nhiều trường phái ẩm thực mới.</p>
         <!-- <a href="menu.html" class="btn">our menu</a> -->
      </div>

   </div>

</section>

<!-- about section ends -->

<!-- steps section starts  -->

</section>

<section class="category">

   <h1 class="title">Danh mục công thức</h1>

   <div class="box-container">

      <a href="category_recipe.php?category=Gia đình" class="box">
         <img src="images/cat-1.png" alt="">
         <h3>Gia Đình</h3>
      </a>

      <a href="category_recipe.php?category=Tiệc" class="box">
         <img src="images/cat-2.png" alt="">
         <h3>Tiệc</h3>
      </a>

      <a href="category_recipe.php?category=Đồ uống" class="box">
         <img src="images/cat-3.png" alt="">
         <h3>Đồ uống</h3>
      </a>

      <a href="category_recipe.php?category=Ăn vặt" class="box">
         <img src="images/cat-4.png" alt="">
         <h3>Ăn vặt</h3>
      </a>

   </div>

</section>




<section class="products">

   <h1 class="title">Công thức đơn giản</h1>

   <div class="box-container">

      <?php
         $select_products = $conn->prepare("SELECT * FROM `recipe` LIMIT 6");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <a href="quick_view_recipe.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex">
            <!-- <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2"> -->
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>

   </div>

   <div class="more-btn">
      <a href="all_recipe.php" class="btn">Xem tất cả</a>
   </div>

</section>

<!-- steps section ends -->

<!-- reviews section starts  -->

<!-- reviews section ends -->



















<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->=






<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   grabCursor: true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
      slidesPerView: 1,
      },
      700: {
      slidesPerView: 2,
      },
      1024: {
      slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>