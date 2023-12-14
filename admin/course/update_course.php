<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_course'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $id_cate = $_POST['id_cate'];
   $id_cate = filter_var($id_cate, FILTER_SANITIZE_STRING);

   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);

   $description = $_POST['description'];

   $opening_day = $_POST['opening_day'];
   $opening_day = filter_var($opening_day, FILTER_SANITIZE_STRING); 

   $study_time = $_POST['study_time'];
   $study_time = filter_var($study_time, FILTER_SANITIZE_STRING);


   $update_product = $conn->prepare("UPDATE `courses` SET name = ?,  id_cate = ?, price = ?, description = ?, opening_day = ?, study_time = ?, admin_id = ? WHERE id = ?");
   $update_product->execute([$name,  $id_cate, $price,  $description, $opening_day, $study_time, $admin_id, $pid ]);

   echo '<script>alert("Cập nhật khóa học thành công!");</script>';   

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../../uploaded_img/'.$image;

   if(!empty($image)){
      if($image_size > 2000000){
        echo '<script>alert("Kích thước hình ảnh quá lớn!");</script>';   
    }else{
         $update_image = $conn->prepare("UPDATE `courses` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);
         move_uploaded_file($image_tmp_name, $image_folder);
         unlink('../../uploaded_img/'.$old_image);
         echo '<script>alert("Hình ảnh đã được cập nhật!");</script>';   
      }
   }
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
            <h1>Cập nhật khóa học</h1>
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
                $update_id = $_GET['update_course'];
                $show_products = $conn->prepare("SELECT * FROM `courses` WHERE id = ?");
                $show_products->execute([$update_id]);
                if($show_products->rowCount() > 0){
                    while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
            ?>
            <form id="quickForm" action="" method="POST" enctype="multipart/form-data"required>
                <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
                <div class="card-body">
                <div class = "row">
                  <div class = "col-md-6">
                      <div class="form-group" >
                        <label for="exampleInputName1">Tên khóa học</label>
                        <input type="text" name="name" class="form-control" placeholder="Tên khóa học" value="<?= $fetch_products['name']; ?>" required>
                      </div>
                  </div>
                  <div class = "col-md-6">
                    <div class="form-group">
                      <label for="exampleSelectBorder">Danh mục</label>
                      
                      <select class="custom-select form-control-border" name="id_cate" id="exampleSelectBorder">
                      <?php
                        $select_courses = $conn->prepare("SELECT * FROM `category`");
                        $select_courses->execute();

                        $selected_id_cate = ''; // Khởi tạo biến để lưu id_cate của đối tượng đã được chọn.

                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_cate"])) {
                            $selected_id_cate = $_POST["id_cate"]; // Lấy giá trị đã được chọn từ biểu mẫu nếu có.
                        }

                        while ($fetch_courses = $select_courses->fetch(PDO::FETCH_ASSOC)) {
                            $id_cate = $fetch_courses['id_cate'];
                            $name_cate = $fetch_courses['name_cate'];
                            
                            // Kiểm tra nếu id_cate của dòng dữ liệu trùng với id_cate đã được chọn.
                            $selected = ($id_cate == $selected_id_cate) ? 'selected' : '';
                            ?>
                            <option value="<?php echo $id_cate; ?>" <?php echo $selected; ?>><?php echo $name_cate; ?></option>
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
                        <label for="exampleInputName1">Giá</label>
                        <input type="text" name="price" class="form-control" placeholder="Giá" value="<?= $fetch_products['price']; ?>" required>
                    </div>
                  </div>
                  <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Hình ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/jpg, image/jpeg, image/png, image/webp" value="<?= $fetch_products['image']; ?>">

                    </div>
                  </div>  
                </div>
                  <div class="form-group" >
                    <label for="exampleInputName1">Mô tả</label>
                    <textarea name="description" id="editor" class="form-control" ><?= $fetch_products['description']; ?></textarea>
                    <script>
                    CKEDITOR.replace('editor');
                    </script>
                </div>
                <div class = "row">
                  <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Ngày khai giảng</label>
                        <input type="date" name="opening_day" class="form-control" placeholder="Ngày khai giảng" value="<?= $fetch_products['opening_day']; ?>" required>
                    </div>
                    </div>
                    <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Thời gian học</label>
                        <input type="text" name="study_time" class="form-control" placeholder="Thời gian học" value="<?= $fetch_products['study_time']; ?>" required>
                    </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "update_course" class="btn btn-primary">Cập nhật</button>
                  <a href="list_course.php" class="btn btn-primary">Trở về</a>

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
