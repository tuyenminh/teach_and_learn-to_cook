<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};


   if(isset($_POST['update_schedule'])){

    $uid = $_POST['id'];
    $uid = filter_var($uid, FILTER_SANITIZE_STRING);

    $time_start = $_POST['time_start'];
    $time_start = filter_var($time_start, FILTER_SANITIZE_STRING);
    
    $time_end = $_POST['time_end'];
    $time_end = filter_var($time_end, FILTER_SANITIZE_STRING);
 
    $id_teacher = $_POST['t_id'];
    $id_teacher = filter_var($id_teacher, FILTER_SANITIZE_STRING);
 
    $courses_id = $_POST['c_id'];
    $courses_id = filter_var($courses_id, FILTER_SANITIZE_STRING);
 
    $weekday_name = $_POST['week'];
    $weekday_name = filter_var($weekday_name, FILTER_SANITIZE_STRING);

    // Thực hiện kiểm tra xem id_teacher, courses_id, weekday_name có tồn tại trong bảng teacher, courses, weekdays hay không.
    // Nếu không tồn tại, bạn có thể thực hiện các xử lý phù hợp, ví dụ: thông báo lỗi.

    $update_users = $conn->prepare("UPDATE `schedule` SET time_start = ?, time_end = ?, t_id = ?, c_id = ?, week = ? WHERE id = ?");
    $update_users->execute([$time_start, $time_end, $id_teacher, $courses_id, $weekday_name, $uid]);

    echo '<script>alert("Cập nhật thành công!");</script>';   
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
            <h1>Cập nhật lịch học</h1>
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
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <?php
                $update_id = $_GET['update_schedule'];
                $show_users = $conn->prepare("SELECT * FROM `schedule` WHERE id = ?");
                $show_users->execute([$update_id]);
                if($show_users->rowCount() > 0){
                    while($fetch_accounts = $show_users->fetch(PDO::FETCH_ASSOC)){  
            ?>
              <form id="quickForm" action="" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?= $fetch_accounts['id']; ?>">
                <div class="card-body">
                    <div class = "row">
                        <div class= "col-md-6">
                            <div class="form-group">
                                <label for="exampleSelectBorder">Giảng viên</label>
                                
                                <select class="custom-select form-control-border" name="t_id" id="exampleSelectBorder">
    <?php
    $select_teachers = $conn->prepare("SELECT * FROM `teacher`");
    $select_teachers->execute();

    $selected_teacher_id = $id_teacher; // Biến này lưu trữ giá trị cần hiển thị, có thể là giá trị được truyền từ dữ liệu cơ sở dữ liệu.

    while ($fetch_teacher = $select_teachers->fetch(PDO::FETCH_ASSOC)) {
        $id_teacher = $fetch_teacher['id'];
        $name_teacher = $fetch_teacher['name'];
        $selected = ($id_teacher == $selected_teacher_id) ? 'selected' : '';
        ?>
        <option value="<?php echo $id_teacher; ?>" <?php echo $selected; ?>><?php echo $name_teacher; ?></option>
        <?php
    }
    ?>
</select>

                            </div>
                        </div>
                        <div class= "col-md-6">
                            <div class="form-group">
                                <label for="exampleSelectBorder">Khóa học</label>
                                <select class="custom-select form-control-border" name="c_id" id="exampleSelectBorder">
    <?php
    $select_courses = $conn->prepare("SELECT * FROM `courses`");
    $select_courses->execute();

    while ($fetch_courses = $select_courses->fetch(PDO::FETCH_ASSOC)) {
        $id_course = $fetch_courses['id'];
        $name_course = $fetch_courses['name'];
        $selected = ($id_course == $courses_id) ? 'selected' : '';
        ?>
        <option value="<?php echo $id_course; ?>" <?php echo $selected; ?>><?php echo $name_course; ?></option>
        <?php
    }
    ?>
</select>

                            </div>
                        </div>    
                    </div>
                    <div class= "row">
                        <div class = "col-md-12">
                                <div class="form-group">
                                    <label for="exampleSelectBorder">Thứ trong tuần</label>
                                    
                                    <select class="custom-select form-control-border" name="week" id="exampleSelectBorder">
            <?php
            $select_weekdays = $conn->prepare("SELECT name FROM `weekdays`");
            $select_weekdays->execute();

            while ($fetch_weekdays = $select_weekdays->fetch(PDO::FETCH_ASSOC)) {
                $weekday_name = $fetch_weekdays['name'];
                $selected = ($weekday_name == $weekday_name) ? 'selected' : '';
                ?>
                <option value="<?php echo $weekday_name; ?>" <?php echo $selected; ?>><?php echo $weekday_name; ?></option>
                <?php
            }
            ?>
        </select>
                                </div>
                        </div>
                    </div>
                    
                    <div class = "row">
                        <div class = "col-md-6">
                            <div class="form-group" >
                                <label for="exampleInputName1">Bắt đầu</label>
                                <input type="text" name="time_start" class="form-control" value="<?= $fetch_accounts['time_start']; ?>" required>
                            </div>
                        </div>
                    <div class = "col-md-6">
                        <div class="form-group" >
                            <label for="exampleInputName1">Kết thúc</label>
                            <input type="text" name="time_end" class="form-control" value="<?= $fetch_accounts['time_end']; ?>" required>
                        </div>
                   
                    </div>
                    
                </div>
                <!-- /.card-body -->
                    <div class="card-footer">
                    <button type="submit" name = "update_schedule" class="btn btn-primary">Cập nhật</button>
                    <a href="list_schedule.php" class="btn btn-primary">Trở về</a>


                    </div>
                ` </form>
                <?php
            }
        }else{
            echo '<p>Không có dữ liệu</p>';
        }
    ?>`
        
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
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
