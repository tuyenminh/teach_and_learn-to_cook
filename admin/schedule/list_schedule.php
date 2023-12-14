<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
  $delete_id = $_GET['delete'];
  $delete_admin = $conn->prepare("DELETE FROM `schedule` WHERE id = ?");
  $delete_admin->execute([$delete_id]);
  header('location:list_schedule.php');
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
    <section class="content-header" >
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Danh sách lịch học</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
            <div class="card">
              <!-- <div class="card-header">
                <h3 class="card-title">Danh sách</h3>
              </div> -->
              <!-- /.card-header -->
              <div class="card-body">
              <table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 3rem; " data-field="id" data-sortable="true">Số thứ tự</th>
            <th style="width: 3rem;">Thứ</th>
            <th style="width: 10rem;">Tên giảng viên</th>
            <th style="width: 10rem;">Khóa học</th>
            <th style="width: 10rem;">Bắt đầu</th>
            <th style="width: 10rem;">Kết thúc</th>
            <th style="width: 6.5rem;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $rows_per_page = 3;

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }

        $offset = ($page - 1) * $rows_per_page;

        $select_account = $conn->prepare("SELECT schedule.id AS id, schedule.time_start AS schedules_time_start, schedule.time_end AS schedules_time_end, teacher.name AS teacher_name, courses.name AS courses_name, weekdays.name AS weekdays_name 
        FROM `schedule` 
        INNER JOIN teacher ON schedule.t_id = teacher.id
        INNER JOIN courses ON schedule.c_id = courses.id
        INNER JOIN weekdays ON schedule.week = weekdays.name

            ORDER BY id DESC
            LIMIT :per_page OFFSET :offset");

        $select_account->bindValue(':offset', $offset, PDO::PARAM_INT);
        $select_account->bindValue(':per_page', $rows_per_page, PDO::PARAM_INT);
        $select_account->execute();

        // Số thứ tự bắt đầu từ vị trí đầu tiên của trang

        // Kiểm tra nếu có dữ liệu trả về
        if ($select_account->rowCount() > 0) {
            while ($fetch_admin = $select_account->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                <td style=""><?php echo $fetch_admin['id']; ?></td>
                    <td style=""><?php echo $fetch_admin['weekdays_name']; ?></td>
                    <td style=""><?php echo $fetch_admin['teacher_name']; ?></td>
                    <td style=""><?php echo $fetch_admin['courses_name']; ?></td>
                    <td style=""><?php echo $fetch_admin['schedules_time_start']; ?></td>
                    <td style=""><?php echo $fetch_admin['schedules_time_end']; ?></td>
                    <td class="form-group">
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item">
                                            <a href="update_schedule.php?update_schedule=<?= $fetch_admin['id']; ?>"><button class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i> </button></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="list_schedule.php?delete=<?= $fetch_admin['id']; ?>" type="button" onclick="return confirm('Bạn có chắn xóa lịch học này? ');"><button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></button></a>
                                        </li>
                                    </ul>
                                </td>
                </tr>
                <?php
            }
        }

        // Tính toán và hiển thị phân trang
        $total_rows = $conn->query("SELECT count(*) FROM `schedule`")->fetchColumn();
        $total_pages = ceil($total_rows / $rows_per_page);

        $list_page = "";

        for ($i = 1; $i <= $total_pages; $i++) {
            $list_page .= '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/schedule/list_schedule.php?page=' . $i . '">' . $i . '</a></li>';
        }
        ?>
    </tbody>
</table>

</div>
<!-- /.card-body -->
<div class="card-footer clearfix">
    <ul class="pagination pagination-sm m-0 float-right">
        <?php
        if (is_numeric($page) && $page > 1) {
            echo '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/schedule/list_schedule.php?page=1">&laquo;&laquo; Trang đầu</a></li>';
        }

        echo $list_page; // Hiển thị liên kết phân trang

        if (is_numeric($page) && $page < $total_pages) {
            echo '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/schedule/list_schedule.php?page=' . $total_pages . '">Trang cuối &raquo;&raquo;</a></li>';
        }
        ?>

                                </ul>
                            </div>
        </div>
            <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<?php include ('../../components/footer.php');?>
</body>
</html>
