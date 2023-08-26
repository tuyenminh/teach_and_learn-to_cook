<?php

include '../components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $name = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE email = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   
   if($select_admin->rowCount() > 0){
      $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
      $_SESSION['admin_id'] = $fetch_admin_id['id'];
      header('location:dashboard.php');
   }else{
      $message[] = 'Tên tài khoản hoặc mật khẩu sai!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Đăng nhập</title>

      <!-- font awesome cdn link  -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

      <!-- custom css file link  -->
      <link rel="stylesheet" href="../css/admin.css">
      <link href="css/bootstrap.min.css" rel="stylesheet">

   </head>
   <body>
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
   <!-- admin login form section starts  -->
      <section class="form-container">

         <form style = "width: 50rem ; border:var(--border);" action="" method="POST">
            <h3>Đăng nhập</h3>
            <!-- <p>default username = <span>admin</span> & password = <span>111</span></p> -->
            <input type="email" name="email" maxlength="20" required placeholder="Nhập email" class="box" >
            <input type="password" name="pass" maxlength="20" required placeholder="Nhập mật khẩu" class="box" >
            <div class="checkbox" style = "right: 175px; ">
               <label>
                  <input name="remember" type="checkbox" value="Remember Me">
                     Nhớ tài khoản
               </label>
            </div>
            <input type="submit" value="Đăng nhập" name="submit" class="btn">
         </form>

      </section>
   <!-- admin login form section ends -->
   </body>
</html>