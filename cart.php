<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
   $message[] = 'Khóa học đã xóa!';
}

if(isset($_POST['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
   $message[] = 'Xóa tất cả khóa học!';
}

// if(isset($_POST['update_qty'])){
//    $cart_id = $_POST['cart_id'];
//    $qty = $_POST['qty'];
//    $qty = filter_var($qty, FILTER_SANITIZE_STRING);
//    $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
//    $update_qty->execute([$qty, $cart_id]);
//    $message[] = 'cart quantity updated';
// }

// $grand_total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Giỏ hàng</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="home-/css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>Giỏ hàng</h3>
   <p><a href="home.php">Trang chủ</a> <span> / Giỏ hàng</span></p>
</div>

<!-- shopping cart section starts  -->

<section class="products">

   <h1 class="title">Giỏ hàng của bạn</h1>

   <div class="box-container">
      <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-times" name="delete" onclick="return confirm(Xóa khóa học?');"></button>
         <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
         <div class="name"><?= $fetch_cart['name']; ?></div>
         <div class="flex">
            <div class="price"><?= number_format($fetch_cart['price']) . " VNĐ"; ?></div>
            <!-- <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" maxlength="2"> -->
            <!-- <button type="submit" class="fas fa-edit" name="update_qty"></button> -->
         </div>
         <div class="sub-total"> Giá: <span><?= $sub_total = number_format($fetch_cart['price']) . " VNĐ"; ?></span> </div>
      </form>
      <?php
               $grand_total += str_replace([',', ' VNĐ'], '', $sub_total);

            }
         }else{
            echo '<p class="empty">Giỏ hàng trống</p>';
         }
      ?>

   </div>

   <div class="cart-total">
      <p>Tổng tiền: <span><?php echo number_format($grand_total). " VNĐ"; ?></span></p>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Tiến hành thanh toán</a>
   </div>

   <div class="more-btn">
      <form action="" method="post">
         <button type="submit" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" name="delete_all" onclick="return confirm('Xóa tất cả khóa học?');">Xóa tất cả</button>
      </form>
      <a href="menu.php" class="btn">Tiếp tục đăng kí</a>
   </div>

</section>

<!-- shopping cart section ends -->










<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->








<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>