<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_schedule'])){

  
   $time_start = $_POST['time_start'];
   $time_start = filter_var($time_start, FILTER_SANITIZE_STRING);
   
   $time_end = $_POST['time_end'];
   $time_end = filter_var($time_end, FILTER_SANITIZE_STRING);

   $id_teacher = $_POST['t_id'];
   $id_teacher = filter_var($id_teacher, FILTER_SANITIZE_STRING);

   $courses_id = $_POST['c_id'];
   $courses_id = filter_var($courses_id, FILTER_SANITIZE_STRING);

   $weekday_id = $_POST['week'];
   $weekday_id = filter_var($weekday_id, FILTER_SANITIZE_STRING);

   $select_products = $conn->prepare("SELECT * FROM `schedule` WHERE time_start = ? AND time_end = ? AND t_id = ? AND c_id = ? AND week = ?");
   $select_products->execute([$time_start, $time_end, $id_teacher, $courses_id, $weekday_id]);

   if($select_products->rowCount() > 0){
    echo '<script>alert("Lịch học đã tồn tại!");</script>';   
}
         $insert_product = $conn->prepare("INSERT INTO `schedule`(time_start, time_end, t_id, c_id, week) VALUES(?,?,?,?,?)");
         $insert_product->execute([$time_start, $time_end, $id_teacher, $courses_id, $weekday_id]);

         echo '<script>alert("Tạo lịch học thành công!");</script>';   
      

   

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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Thêm lịch học</h1>
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
              <form id="quickForm" action="" method="POST" enctype="multipart/form-data"required>
                <div class="card-body">
                <div class = "row">
                  <div class = "col-md-6">
                  <div class="form-group">
                      <label for="exampleSelectBorder">Giảng viên</label>
                        <select class="custom-select form-control-border" name="t_id" id="teacherSelect">
                        <?php
                        $select_courses = $conn->prepare("SELECT * FROM `teacher` ORDER BY name ASC");
                        $select_courses->execute();
                        while($fetch_courses = $select_courses->fetch(PDO::FETCH_ASSOC)){ ?>
                          <option value="<?php echo $fetch_courses['id'] ?>"><?php echo $fetch_courses['name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class = "col-md-6">
                    <div class="form-group">
                      <label for="exampleSelectBorder">Khóa học</label>
                      <select class="custom-select form-control-border" name="c_id" id="courseSelect">
                        <?php
                        $select_courses = $conn->prepare("SELECT * FROM `courses` ORDER BY name ASC");
                        $select_courses->execute();
                        while($fetch_courses = $select_courses->fetch(PDO::FETCH_ASSOC)){ ?>
                          <option value="<?php echo $fetch_courses['id'] ?>"><?php echo $fetch_courses['name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div>
                <div class="form-group">
                      <label for="exampleSelectBorder">Thứ trong tuần</label>
                      <select class="custom-select form-control-border" name="week" id="weekSelect">
                        <?php
                        $select_weekdays = $conn->prepare("SELECT * FROM `weekdays` ORDER BY name ASC");
                        $select_weekdays->execute();
                        while($fetch_weekdays = $select_weekdays->fetch(PDO::FETCH_ASSOC)){ ?>
                          <option value="<?php echo $fetch_weekdays['name']; ?>"><?php echo $fetch_weekdays['name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                </div>
                <div class = "row">
                  <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Thời gian bắt đầu</label>
                        <input type="text" name="time_start" class="form-control" placeholder="Thời gian bắt đầu" required>
                    </div>
                  </div>
                  <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Thời gian kết thúc</label>
                        <input type="text" name="time_end" class="form-control" placeholder="Thời gian kết thúc" required>
                    </div>
                  </div>  
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "add_schedule" class="btn btn-primary">Thêm</button>
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
