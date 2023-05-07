<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="dashboard.php" class="logo">Admin<span></span></a>

      <nav class="navbar">
         <a href="dashboard.php">Trang chủ</a>
         <a href="products.php">Khóa học</a>
         <a href="recipe.php">Công thức</a>
         <a href="placed_orders.php">Đăng kí</a>
         <a href="messages.php">Liên hệ</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">Cập nhật hồ sơ</a>
         <div class="flex-btn">
            <a href="admin_login.php" class="option-btn">Đăng nhập</a>
            <a href="register_admin.php" class="option-btn">Đăng kí</a>
         </div>
         <a href="../components/admin_logout.php" onclick="return confirm('Đăng xuất khỏi trang web này?');" class="delete-btn">Đăng xuất</a>
      </div>

   </section>

</header>