<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_category'])){

   $name = $_POST['name_cate'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $select_users = $conn->prepare("SELECT * FROM `category` WHERE name_cate = ?" );
   $select_users->execute([$name]);

   if($select_users->rowCount() > 0){
        echo '<script>alert("Danh mục đã tồn tại!");</script>';   
    }else{
   
         $insert_users = $conn->prepare("INSERT INTO `category`(name_cate, admin_id) VALUES(?, ?)");
         $insert_users->execute([$name, $admin_id]);

         echo '<script>alert("Tạo danh mục thành công!");</script>';   
      }
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>

<?php include ('../../components/head.php');?>

<body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

  <!-- Navbar -->
  <?php include ('../../components/navbar.php');?>

  <?php include ('../../components/sidebar.php');?>
      <!-- /.sidebar-menu -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" >
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Thêm danh mục</h1>
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
              <form id="quickForm" action="" method="POST" required>
                <div class="card-body">
                <div class="form-group" >
                    <label for="exampleInputName1">Tên danh mục</label>
                    <input type="text" name="name_cate" class="form-control" placeholder="Tên danh mục" required>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "add_category" class="btn btn-primary">Thêm</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
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
