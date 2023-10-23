<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};
if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_recipe_image = $conn->prepare("SELECT * FROM `recipe` WHERE id = ?");
   $delete_recipe_image->execute([$delete_id]);
   $fetch_delete_image = $delete_recipe_image->fetch(PDO::FETCH_ASSOC);
   unlink('../../uploaded_img/'.$fetch_delete_image['image']);
   $delete_recipe = $conn->prepare("DELETE FROM `recipe` WHERE id = ?");
   $delete_recipe->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:list_recipe.php');

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
  <?php include ('../../components/head.php');?>
</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
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
    <section class="content-header" style ="padding-top: 70px;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Danh sách công thức</h1>
          </div>
          <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
              <li class="breadcrumb-item active">Tài khoản Admin</li>
            </ol>
          </div>
        </div> -->
      </div><!-- /.container-fluid -->
      <div id="message"></div>
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
						<th style = "width: 10rem;">Tên công thức </th>
                        <th style = "width: 7rem;">Danh mục </th>
                        <th style = "width: 7rem;">Thời gian </th>
                        <th style = "width: 10rem;">Hình ảnh</th>
                        <th style = "width: 20rem;">Cách làm</th>
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
                    $select_account = $conn->prepare("SELECT * FROM `recipe` INNER JOIN category ON recipe.id_cate = category.id_cate ORDER BY recipe.id DESC LIMIT :per_page OFFSET :offset");
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
                                <td style=""><?php echo $fetch_admin['name_cate']; ?></td>
                                <td style=""><?php echo $fetch_admin['time']; ?></td>
                                <td><img style = "heigth: 10rem; width: 10rem;" 
                                          src="../../uploaded_img/<?= $fetch_admin['image']; ?>" alt="">
                                </td>
                                <td>
                                    <div style="
                                        display: -webkit-box;
                                        font-size: 16px;
                                        line-height: 1.2;
                                        -webkit-line-clamp: 5;  /* số dòng hiển thị */
                                        -webkit-box-orient: vertical;
                                        overflow: hidden;
                                        text-overflow: ellipsis;">
                                        <?php echo $fetch_admin['making']; ?>
                                    </div>
                                </td>
                                <td class="form-group">
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item">
                                            <a href="update_recipe.php?update_recipe=<?= $fetch_admin['id']; ?>"><button class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i> </button></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="list_recipe.php?delete=<?= $fetch_admin['id']; ?>" type="button" onclick="return confirm('Bạn có chắn xóa khóa học này? ');"><button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></button></a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <?php
                        }
                    }

                    // Tính toán và hiển thị phân trang
                    $total_rows = $conn->query("SELECT count(*) FROM `recipe`")->fetchColumn();
                    $total_pages = ceil($total_rows / $rows_per_page);

                    $list_page = "";

                    for ($i = 1; $i <= $total_pages; $i++) {
                        $list_page .= '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/recipe/list_recipe.php?page=' . $i . '">' . $i . '</a></li>';
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
                    echo '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/recipe/list_recipe.php?page=1">&laquo;&laquo; Trang đầu</a></li>';
                }

                echo $list_page;

                if ($page < $total_pages) {
                    echo '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/recipe/list_recipe.php?page=' . $total_pages . '">Trang cuối &raquo;&raquo;</a></li>';
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
