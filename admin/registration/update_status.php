<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_status'])) {
  $order_id = $_POST['order_id'];
  $status = $_POST['status'];
  
  // Lấy admin_id từ phiên làm việc
  $admin_id = $_SESSION['admin_id'];

  $update_status = $conn->prepare("UPDATE `registration_form` SET status = ?, admin_id = ? WHERE id = ?");
  if ($update_status->execute([$status, $admin_id, $order_id])) {
    echo '<script>alert("Cập nhật thành công trạng thái!");</script>';
  } else {
    echo '<script>alert("Cập nhật trạng thái thất bại!");</script>';
  }
}



if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `registration_form` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:http://localhost/teach_and_learn-to_cook/admin/registration/regis_form.php');
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
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Cập nhật trạng thái phiếu đăng ký</h1>
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
                $update_id = $_GET['update_status'];
                $select_orders = $conn->prepare("SELECT registration_form.id, users.id AS user_id, users.number, users.name, users.email, 
                courses.name AS courses_name, courses.price AS courses_price, 
                registration_form.status AS courses_status,
                courses.id_cate AS courses_id_cate,
                category.name_cate AS category
            FROM registration_form
            INNER JOIN users ON registration_form.user_id = users.id
            INNER JOIN courses ON registration_form.course_id = courses.id
            INNER JOIN category ON courses.id_cate = category.id_cate
            WHERE registration_form.id = ?");
                $select_orders->execute([$update_id]);
                if($select_orders->rowCount() > 0){
                    while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){  
            ?>
            <form id="quickForm" action="" method="POST" enctype="multipart/form-data"required>
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">

                <div class="card-body">
                <div class = "row">
                  <div class = "col-md-6">
                      <div class="form-group" >
                        <label for="exampleInputName1">Tên khách hàng </label>
                        <input type="text" class="form-control" placeholder="Tên khóa học" value="<?= $fetch_orders['name']; ?>" readonly>
                      </div>
                  </div>
                  <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Email</label>
                        <input type="text"  class="form-control" placeholder="Tên khóa học" value="<?= $fetch_orders['email']; ?>" readonly>
                    </div>
                  </div>
                </div>
                <div class = "row">
                  <div class = "col-md-6">
                      <div class="form-group" >
                        <label for="exampleInputName1">Số điện thoại</label>
                        <input type="text" class="form-control" placeholder="Tên khóa học" value="<?= $fetch_orders['number']; ?>" readonly>
                      </div>
                  </div>
                  <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Tên khóa học</label>
                        <input type="text" class="form-control" placeholder="Tên khóa học" value="<?= $fetch_orders['courses_name']; ?>" readonly>
                    </div>
                  </div>
                </div>
                <div class = "row">
                  <div class = "col-md-6">
                      <div class="form-group" >
                        <label for="exampleInputName1">Giá</label>
                        <input type="text" class="form-control" placeholder="Tên khóa học" value="<?= number_format($fetch_orders['courses_price'], 0, ',', '.') . " VNĐ"; ?>" readonly>
                      </div>
                  </div>
                  <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Danh mục</label>
                        <input type="text" class="form-control" placeholder="Tên khóa học" value="<?= $fetch_orders['category']; ?>" readonly>
                    </div>
                  </div>
                </div>
                <div class="form-group" >
                        <label for="exampleInputName1">Trạng thái</label>
                        <select class="custom-select form-control-border"name="status" id="exampleSelectBorder">
                          <option value="" selected disabled><?= $fetch_orders['courses_status'];?></option>
                          <option value="Đang xử lý">Đang xử lý</option>
                          <option value="Đã hoàn thành">Đã hoàn thành</option>
                      </select>
                    </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "update_status" class="btn btn-primary">Cập nhật</button>
                  <a href="list_regis_form.php" class="btn btn-primary">Trở về</a>

                </div>
              </form>
              <?php
         }
      }else{
        echo '<script>alert("Không có dữ liệu!");</script>';   
    }
   ?>
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
