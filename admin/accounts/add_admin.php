<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $email = $_POST['email'];
   $number = $_POST['number'];
   $address = $_POST['address'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);
   
   if($select_admin->rowCount() > 0){
    echo '<script>alert("Tài khoản đã tồn tại!");</script>';   
  }else{
      if($pass != $cpass){
        echo '<script>alert("Xác nhận mật khẩu không đúng");</script>';   
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, email, number, address, password) VALUES(?,?,?,?,?)");
         $insert_admin->execute([$name, $email, $number, $address, $cpass]);
         echo '<script>alert("Tạo tài khoản thành công!");</script>';   
        }
   }

}

?>

<!DOCTYPE html>
<html lang="en">

  <?php include ('../../components/head.php');?>

<body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

  <!-- Navbar -->
  <?php include ('../../components/navbar.php');?>

  <?php include ('../../components/sidebar.php');?>
      <!-- /.sidebar-menu -->


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Thêm quản trị viên</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
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
                  <div class = "row">
                    <div class = "col-md-6">
                      <div class="form-group" >
                          <label for="exampleInputName1">Tên tài khoản</label>
                          <input type="text" name="name" class="form-control" placeholder="Tên tài khoản" required>
                        </div>
                      </div>
                      <div class = "col-md-6">
                        <div class="form-group">
                          <label for="exampleInputEmail1">Email</label>
                          <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                      </div>
                    </div> 
                    <div class = "row">
                      <div class = "col-md-6">
                        <div class="form-group">
                          <label for="exampleInputNumber1">Số điện thoại</label>
                          <input type="text" name="number" class="form-control" placeholder="Số điện thoại" required>
                        </div>
                      </div>    
                      <div class = "col-md-6">
                        <div class="form-group">
                          <label for="exampleInputAddress1">Địa chỉ</label>
                          <input type="text" name="address" class="form-control" placeholder="Địa chỉ" required>
                        </div>
                      </div>
                    </div>
                    <div class = "row">
                      <div class = "col-md-6">
                        <div class="form-group">
                          <label for="exampleInputPassword1">Password</label>
                          <input type="password" name="pass" class="form-control" placeholder="Mật khẩu" required>
                        </div>
                      </div> 
                      <div class = "col-md-6">
                        <div class="form-group">
                          <label for="exampleInputCPassword1">Xác nhận Password</label>
                          <input type="password" name="cpass" class="form-control" placeholder="Nhập lại mật khẩu" required>
                        </div>
                      </div>
                    </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "submit" class="btn btn-primary">Thêm</button>
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
