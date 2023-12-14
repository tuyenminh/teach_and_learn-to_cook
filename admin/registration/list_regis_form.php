<?php

   include '../../components/connect.php';

   session_start();

   $admin_id = $_SESSION['admin_id'];

   if(!isset($admin_id)){
      header('location:admin_login.php');
   };

   if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_order = $conn->prepare("DELETE FROM `registration_form` WHERE id = ?");
    $delete_order->execute([$delete_id]);
    header('location:http://localhost/teach_and_learn-to_cook/admin/registration/list_regis_form.php');
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
            <h1>Danh sách phiếu đăng ký</h1>
          </div>
      </div><!-- /.container-fluid -->
      <div id="message"></div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">Mã ĐK</th>
						<th>Tên khách hàng </th>
                        <th>Email</th>
                        <th>Số điện thoại </th>
                        <th>Tên khóa học</th>
                        <th>Danh mục</th>
                        <th>Giá </th>
                        <th>Trạng thái</th>
						<th style = "width: 7rem;">Hành động</th>
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
                    $select_account = $conn->prepare("SELECT registration_form.id,
                                                    users.id AS user_id, users.number, users.name, users.email, 
                                                    courses.name AS courses_name, courses.price AS courses_price, 
                                                    registration_form.status AS courses_status,
                                                    courses.id_cate AS courses_id_cate,
                                                    category.name_cate AS category
                                                FROM registration_form
                                                INNER JOIN users ON registration_form.user_id = users.id
                                                INNER JOIN courses ON registration_form.course_id = courses.id
                                                INNER JOIN category ON courses.id_cate = category.id_cate ORDER BY courses.id DESC LIMIT :per_page OFFSET :offset");
                    $select_account->bindValue(':offset', $offset, PDO::PARAM_INT);
                    $select_account->bindValue(':per_page', $rows_per_page, PDO::PARAM_INT); // Thêm dòng này để định nghĩa tham số :per_page
                    $select_account->execute();
                    // Kiểm tra nếu có dữ liệu trả về
                    if ($select_account->rowCount() > 0) {
                        // Duyệt và xử lý dữ liệu
                        while ($fetch_admin = $select_account->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <tr>
                                <td style=""><?php echo $fetch_admin['id']; ?></td>
                                <td style=""><?php echo $fetch_admin['name']; ?></td>
                                <td style=""><?php echo $fetch_admin['email']; ?></td>
                                <td style=""><?php echo $fetch_admin['number']; ?></td>
                                <td style=""><?php echo $fetch_admin['courses_name']; ?></td>
                                <td style=""><?php echo $fetch_admin['category']; ?></td>

                                <td style=""><?php echo number_format($fetch_admin['courses_price'], 0, ',', '.') . " VNĐ" ?></td>
                                <td style=""><?php echo $fetch_admin['courses_status']; ?></td>
                                <td class="form-group">
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item">
                                            <a href="update_status.php?update_status=<?= $fetch_admin['id']; ?>"><button class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i> </button></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="list_regis_form.php?delete=<?= $fetch_admin['id']; ?>" type="button" onclick="return confirm('Bạn có chắn xóa phiếu ĐK này? ');"><button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></button></a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <?php
                        }
                    }

                    // Tính toán và hiển thị phân trang
                    $total_rows = $conn->query("SELECT count(*) FROM `registration_form`")->fetchColumn();
                    $total_pages = ceil($total_rows / $rows_per_page);

                    $list_page = "";

                    for ($i = 1; $i <= $total_pages; $i++) {
                        $list_page .= '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/registration/list_regis_form.php?page=' . $i . '">' . $i . '</a></li>';
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
                      echo '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/registration/list_regis_form.php?page=1">&laquo;&laquo; Trang đầu</a></li>';
                  }

                  for ($i = 1; $i <= $total_pages; $i++) {
                      $activeClass = ($i == $page) ? 'active' : '';
                      echo '<li class="page-item ' . $activeClass . '"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/registration/list_regis_form.php?page=' . $i . '">' . $i . '</a></li>';
                  }

                  if (is_numeric($page) && $page < $total_pages) {
                      echo '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/registration/list_regis_form.php?page=' . $total_pages . '">Trang cuối &raquo;&raquo;</a></li>';
                  }
              ?>
            </ul>
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
