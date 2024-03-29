<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../components/connect.php';

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
      header('location:admin.php');
   } else {
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
   </head>
   <style>
    @import url(https://fonts.googleapis.com/css?family=Roboto:300);

.login-page {
  width: 360px;
  padding: 8% 0 0;
  margin: auto;
}
.form {
  position: relative;
  z-index: 1;
  background: var(--blue);
  background: #FFFFFF;
  max-width: 360px;
  margin: 0 auto 100px;
  padding: 45px;
  text-align: center;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.form input {
  font-family: "Roboto", sans-serif;
  outline: 0;
  background: #f2f2f2;
  width: 100%;
  border: 0;
  margin: 0 0 15px;
  padding: 15px;
  box-sizing: border-box;
  font-size: 15px;

}
.form button {
  font-family: "Roboto", sans-serif;
  text-transform: uppercase;
  outline: 0;
  background: #6f67bd;
  width: 100%;
  border: 0;
  padding: 15px;
  color: #FFFFFF;
  font-size: 14px;
  -webkit-transition: all 0.3 ease;
  transition: all 0.3 ease;
  cursor: pointer;
}
.form button:hover,.form button:active,.form button:focus {
  background: #337ab7;
}
.form .message {
  margin: 15px 0 0;
  color: #b3b3b3;
  font-size: 15px;
}
.form .message a {
  color: #6f67bd;
  text-decoration: none;
}
.form .register-form {
  display: none;
}
.container {
  position: relative;
  z-index: 1;
  max-width: 300px;
  margin: 0 auto;
}
.container:before, .container:after {
  content: "";
  display: block;
  clear: both;
}
.container .info {
  margin: 50px auto;
  text-align: center;
}
.container .info h1 {
  margin: 0 0 15px;
  padding: 0;
  font-size: 36px;
  font-weight: 300;
  color: #1a1a1a;
}
.container .info span {
  color: #4d4d4d;
  font-size: 12px;
}
.container .info span a {
  color: #000000;
  text-decoration: none;
}
.container .info span .fa {
  color: #EF3B3A;
}
body {
  font-family: "Roboto", sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;      
}
   </style>
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
   <div class="login-page">
  <div class="form" >
    <form class="login-form" action="" method="POST">
      <h2 style="font-weight: bold;" >Đăng nhập</h2>
      <input type="email" name = "email" placeholder="Email" require/>
      <input type="password" name = "pass" placeholder="Mật khẩu"/>
      <button type = "submit"  name="submit">Đăng nhập</button>
    </form>
  </div>
</div>
<script>
    $('.message a').click(function(){
   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
});
</script>
   </body>
</html>
