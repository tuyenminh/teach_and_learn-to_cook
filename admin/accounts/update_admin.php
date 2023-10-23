<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};


   if(isset($_POST['update_admin'])){

   $uid = $_POST['id'];
   $uid = filter_var($uid, FILTER_SANITIZE_STRING);

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


   $update_users = $conn->prepare("UPDATE `admin` SET name = ?, email = ?, number = ?, address = ?, password = ? WHERE id = ?");
   $update_users->execute([$name, $email, $number,   $address, $password, $uid]);

   echo '<script>alert("Cập nhật thành công!");</script>';   
}

?>
<!DOCTYPE html>
<html lang="en">

<?php include ('../../components/head.php');?>

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
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" style ="padding-top: 70px;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Cập nhật quản trị viên</h1>
          </div>

        </div>
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
              <?php
                $update_id = $_GET['update_admin'];
                $show_users = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
                $show_users->execute([$update_id]);
                if($show_users->rowCount() > 0){
                    while($fetch_accounts = $show_users->fetch(PDO::FETCH_ASSOC)){  
            ?>
              <form id="quickForm" action="" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?= $fetch_accounts['id']; ?>">
                <div class="card-body">
                  <div class = "row">
                    <div class = "col-md-6">
                      <div class="form-group" >
                          <label for="exampleInputName1">Tên tài khoản</label>
                          <input type="text" name="name" class="form-control" value="<?= $fetch_accounts['name']; ?>" required>
                        </div>
                      </div>
                      <div class = "col-md-6">
                        <div class="form-group">
                          <label for="exampleInputEmail1">Email</label>
                          <input type="email" name="email" class="form-control" value="<?= $fetch_accounts['email']; ?>" required>
                        </div>
                      </div>
                    </div>
                    <div class = "row">
                      <div class = "col-md-6">
                        <div class="form-group">
                          <label for="exampleInputNumber1">Số điện thoại</label>
                          <input type="text" name="number" class="form-control" value="<?= $fetch_accounts['number']; ?>" required>
                        </div>
                      </div>
                        <div class = "col-md-6">
                        <div class="form-group">
                          <label for="exampleInputAddress1">Địa chỉ</label>
                          <input type="text" name="address" class="form-control" value="<?= $fetch_accounts['address']; ?>" required>
                        </div>
                      </div>
                    </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="password" class="form-control" value="<?= $fetch_accounts['address']; ?>" required>
                  </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "update_admin" class="btn btn-primary">Cập nhật</button>
                  <a href="list_admin.php" class="btn btn-primary">Trở về</a>


                </div>
              </form>
              <?php
         }
      }else{
         echo '<p>Không có dữ liệu</p>';
      }
   ?>
        
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
</html>
