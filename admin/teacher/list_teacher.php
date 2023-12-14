<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admin = $conn->prepare("DELETE FROM `teach` WHERE id = ?");
   $delete_admin->execute([$delete_id]);
   header('location:list_customer.php');
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Danh sách giảng viên</h1>
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
                        <th style = "width: 3rem; " data-field="id" data-sortable="true">STT</th>
						            <th style = "width: 15rem;">Tên giảng viên </th>
                                    <th style = "width: 10rem;">Chức danh</th>

                        <th style = "width: 7rem;">Email</th>
                        <th style = "width: 10rem;">Số điện thoại</th>
						            <th style = " width: 6.5rem;">Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Số dòng trên mỗi trang
                    $rows_per_page = 5;

                    // Trang hiện tại
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                    } else {
                        $page = 1;
                    }

                    // Tính toán OFFSET
                    $offset = ($page - 1) * $rows_per_page;

                    // Truy vấn SQL với LIMIT và OFFSET
                    $select_account = $conn->prepare("SELECT * FROM `teacher` LIMIT :offset, :rows_per_page");
                    $select_account->bindValue(':offset', $offset, PDO::PARAM_INT);
                    $select_account->bindValue(':rows_per_page', $rows_per_page, PDO::PARAM_INT);
                    $select_account->execute();

                    // Kiểm tra nếu có dữ liệu trả về
                    if ($select_account->rowCount() > 0) {
                        // Duyệt và xử lý dữ liệu
                        while ($fetch_admin = $select_account->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <tr>
                                <td style=""><?php echo $fetch_admin['id']; ?></td>
                                <td style=""><?php echo $fetch_admin['name']; ?></td>
                                <td style=""><?php echo $fetch_admin['position']; ?></td>

                                <td style=""><?php echo $fetch_admin['email']; ?></td>
                                <td style=""><?php echo $fetch_admin['number']; ?></td>
                                <td class="form-group">
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item">
                                            <a href="update_teacher.php?update_teacher=<?= $fetch_admin['id']; ?>"><button class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i> </button></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="list_teacher.php?delete=<?= $fetch_admin['id']; ?>" type="button" onclick="return confirm('Xóa tài khoản');"><button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></button></a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <?php
                        }
                    }

                    // Tính toán và hiển thị phân trang
                    $total_rows = $conn->query("SELECT count(*) FROM `teacher`")->fetchColumn();
                    $total_pages = ceil($total_rows / $rows_per_page);

                    $list_page = "";

                    for ($i = 1; $i <= $total_pages; $i++) {
                        $list_page .= '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/teacher/list_teacher.php?page=' . $i . '">' . $i . '</a></li>';
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
                      echo '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/teacher/list_teacher.php?page=1">&laquo;&laquo; Trang đầu</a></li>';
                  }

                  for ($i = 1; $i <= $total_pages; $i++) {
                      $activeClass = ($i == $page) ? 'active' : '';
                      echo '<li class="page-item ' . $activeClass . '"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/teacher/list_teacher.php?page=' . $i . '">' . $i . '</a></li>';
                  }

                  if (is_numeric($page) && $page < $total_pages) {
                      echo '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/teacher/list_teacher.php?page=' . $total_pages . '">Trang cuối &raquo;&raquo;</a></li>';
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
<script src="../js/admin_script.js"></script>
<script src="js/bootstrap-table.js"></script>
</body>
</html>
