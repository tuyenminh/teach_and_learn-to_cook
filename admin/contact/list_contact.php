<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:list_contact.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
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
            <h1 style = "font-weight: bold; text-color: #5586BO;" >Danh sách liên hệ</h1>
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
						            <th style = "width: 10rem;">Tên khách hàng</th>
                                    <th style = "width: 10rem;">Email</th>
						            <th style = "width: 10rem;">Số điện thoại</th>
						            <th style = "width: 20rem;">Lời nhắn</th>
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
                    $select_account = $conn->prepare("SELECT * FROM `messages` LIMIT :offset, :rows_per_page");
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
                                <td style=""><?php echo $fetch_admin['email']; ?></td>
                                <td style=""><?php echo $fetch_admin['number']; ?></td>
                                <td style=""><?php echo $fetch_admin['message']; ?></td>
                                <td class="form-group">
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item">
                                            <a href="list_contact.php?delete=<?= $fetch_admin['id']; ?>" type="button" onclick="return confirm('Bạn có chắn xóa tin nhắn này? ');"><button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></button></a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <?php
                        }
                    }

                    // Tính toán và hiển thị phân trang
                    $total_rows = $conn->query("SELECT count(*) FROM `messages`")->fetchColumn();
                    $total_pages = ceil($total_rows / $rows_per_page);

                    $list_page = "";

                    for ($i = 1; $i <= $total_pages; $i++) {
                        $list_page .= '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/contact/list_contact.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                    ?>


                    </tbody>
                </table>
            </div>
              <!-- /.card-body -->
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
            <?php
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/contact/list_contact.php?page=1">&laquo;&laquo; Trang đầu</a></li>';
                }

                echo $list_page;

                if ($page < $total_pages) {
                    echo '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/contact/list_contact.php?page=' . $total_pages . '">Trang cuối &raquo;&raquo;</a></li>';
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