<?php

   include '../../components/connect.php';

   session_start();

   $admin_id = $_SESSION['admin_id'];

   if(!isset($admin_id)){
      header('location:admin_login.php');
   };
   if(isset($_GET['delete'])){
      
      $delete_id = $_GET['delete'];
      $delete_product_image = $conn->prepare("SELECT * FROM `courses` WHERE id = ?");
      $delete_product_image->execute([$delete_id]);
      $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
      unlink('../../uploaded_img/'.$fetch_delete_image['image']);
      $delete_product = $conn->prepare("DELETE FROM `courses` WHERE id = ?");
      $delete_product->execute([$delete_id]);
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
      $delete_cart->execute([$delete_id]);
      header('location:list_course.php');

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
            <h1>Danh sách khóa học</h1>
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
              <!-- <div class="card-header">
                <h3 class="card-title">Danh sách</h3>
              </div> -->
              <!-- /.card-header -->
              <div class="card-body">
              <div class="search-container">
                
    <!-- <form action="list_course.php" method="GET">
        <input type="text" placeholder="Tìm kiếm theo tên khóa học" name="search_name">
        <select name="search_category">
            <option value="">Tất cả danh mục</option>
            <?php
            // Truy vấn SQL để lấy danh sách danh mục
            $categories_query = $conn->query("SELECT * FROM category");
            while ($category = $categories_query->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$category['id_cate']}'>{$category['name_cate']}</option>";
            }
            ?>
        </select>
        <button type="submit">Tìm kiếm</button>
    </form> -->
</div>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th style = "width: 3rem; " data-field="id" data-sortable="true">STT</th>
						            <th style = "width: 10rem;">Tên khóa học </th>
                        <th style = "width: 7rem;">Danh mục </th>
                        <th style = "width: 7rem;">Giá </th>
                        <th style = "width: 10rem;">Hình ảnh</th>
                        <th style = "width: 10rem;">Ngày khai giảng</th>
                        <th style = "width: 8rem;">Thời gian học</th>
						            <th style = " width: 6.5rem;">Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Số dòng trên mỗi trang
                    $rows_per_page = 3;

                    // Trang hiện tại
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                    } else {
                        $page = 1;
                    }

                    // Tính toán OFFSET
                    $offset = ($page - 1) * $rows_per_page;

                    // Truy vấn SQL với LIMIT và OFFSET
                    $select_account = $conn->prepare("SELECT * FROM `courses` INNER JOIN category ON courses.id_cate = category.id_cate ORDER BY courses.id DESC LIMIT :per_page OFFSET :offset");
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
                                <td style=""><?php echo number_format($fetch_admin['price'], 0, ',', '.') . " VNĐ" ?></td>
                                <td><img style = "heigth: 10rem; width: 10rem;" 
                                          src="../../uploaded_img/<?= $fetch_admin['image']; ?>" alt="">
                                </td>
                                <td style=""><?php echo $fetch_admin['opening_day']; ?></td>
                                <td style=""><?php echo $fetch_admin['study_time']; ?></td>
                                <td class="form-group">
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item">
                                            <a href="update_course.php?update_course=<?= $fetch_admin['id']; ?>"><button class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i> </button></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="list_course.php?delete=<?= $fetch_admin['id']; ?>" type="button" onclick="return confirm('Bạn có chắn xóa khóa học này? ');"><button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></button></a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <?php
                        }
                    }

                    // Tính toán và hiển thị phân trang
                    $total_rows = $conn->query("SELECT count(*) FROM `courses`")->fetchColumn();
                    $total_pages = ceil($total_rows / $rows_per_page);

                    $list_page = "";

                    for ($i = 1; $i <= $total_pages; $i++) {
                        $list_page .= '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/course/list_course.php?page=' . $i . '">' . $i . '</a></li>';
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
                      echo '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/course/list_course.php?page=1">&laquo;&laquo; Trang đầu</a></li>';
                  }

                  for ($i = 1; $i <= $total_pages; $i++) {
                      $activeClass = ($i == $page) ? 'active' : '';
                      echo '<li class="page-item ' . $activeClass . '"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/course/list_course.php?page=' . $i . '">' . $i . '</a></li>';
                  }

                  if (is_numeric($page) && $page < $total_pages) {
                      echo '<li class="page-item"><a class="page-link" href="http://localhost/teach_and_learn-to_cook/admin/course/list_course.php?page=' . $total_pages . '">Trang cuối &raquo;&raquo;</a></li>';
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
