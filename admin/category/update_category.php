<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};


   if(isset($_POST['update_category'])){

   $uid = $_POST['uid_cate'];
   $uid = filter_var($uid, FILTER_SANITIZE_STRING);

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $update_users = $conn->prepare("UPDATE `category` SET name_cate = ? WHERE id_cate = ?");
   $update_users->execute([$name, $uid]);

   echo '<script>alert("Cập nhật thành công!");</script>';   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include ('../../components/head.php');?>
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
            <h1>Cập nhật tài khoản Khách hàng</h1>
          </div>
          <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
              <li class="breadcrumb-item active">Tài khoản Admin</li>
            </ol>
          </div> -->
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
                $update_id = $_GET['update_category'];
                $show_users = $conn->prepare("SELECT * FROM `category` WHERE id_cate = ?");
                $show_users->execute([$update_id]);
                if($show_users->rowCount() > 0){
                    while($fetch_accounts = $show_users->fetch(PDO::FETCH_ASSOC)){  
            ?>
              <form id="quickForm" action="" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="uid_cate" value="<?= $fetch_accounts['id_cate']; ?>">
                <div class="card-body">
                <div class="form-group" >
                    <label for="exampleInputName1">Tên danh mục</label>
                    <input type="text" name="name" class="form-control" value="<?= $fetch_accounts['name_cate']; ?>" required>
                  </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "update_category" class="btn btn-primary">Cập nhật</button>
                  <a href="list_category.php" class="btn btn-primary">Trở về</a>


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
