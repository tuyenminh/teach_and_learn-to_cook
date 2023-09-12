<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_users'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $password = sha1($_POST['password']);
   $password = filter_var($password, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   $select_users = $conn->prepare("SELECT * FROM `users` WHERE name = ? AND email = ?" );
   $select_users->execute([$name, $email]);

   if($select_users->rowCount() > 0){
    echo '<script>alert("Tài khoản đã tồn tại!");</script>';   
   }else{
      

         $insert_users = $conn->prepare("INSERT INTO `users`(name, email, number, password, address) VALUES(?,?,?,?,?)");
         $insert_users->execute([$name, $email, $number,$password, $address ]);

         echo '<script>alert("Tạo tài khoản thành công!");</script>';   
      }

   }

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
  <?php include ('../../components/head.php');?>
</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="../../dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <?php include ('../../components/navbar.php');?>

  <?php include ('../../components/sidebar.php');?>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Thêm tài khoản Khách hàng</h1>
          </div>
          <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
              <li class="breadcrumb-item active">Tài khoản Admin</li>
            </ol>
          </div>
        </div> -->
      </div><!-- /.container-fluid -->
      <div id="message"></div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <!-- <h3 class="card-title">Quick Example <small>jQuery Validation</small></h3> -->
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="quickForm" action="" method="POST" required>
                <div class="card-body">
                <div class="form-group" >
                    <label for="exampleInputName1">Tên tài khoản</label>
                    <input type="text" name="name" class="form-control" placeholder="Tên tài khoản" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputNumber1">Số điện thoại</label>
                    <input type="text" name="number" class="form-control" placeholder="Số điện thoại" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputAddress1">Địa chỉ</label>
                    <input type="text" name="address" class="form-control" placeholder="Địa chỉ" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                  </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "add_users" class="btn btn-primary">Tạo</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
<?php include ('../../components/footer.php');?>

</body>
</html>
