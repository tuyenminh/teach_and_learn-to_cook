<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_course'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   
   $id_cate = $_POST['id_cate'];
   $id_cate = filter_var($id_cate, FILTER_SANITIZE_STRING);

   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);


   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../../uploaded_img/'.$image;


   $description = $_POST['description'];
   $opening_day = $_POST['opening_day'];
   $opening_day = filter_var($opening_day, FILTER_SANITIZE_STRING); 

   $study_time = $_POST['study_time'];
   $study_time = filter_var($study_time, FILTER_SANITIZE_STRING);

   $select_products = $conn->prepare("SELECT * FROM `courses` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
    echo '<script>alert("Khóa học đã tồn tại!");</script>';   
}else{
      if($image_size > 2000000){
        echo '<script>alert("Kích thước ảnh không thích hợp!");</script>';   
    }else{
         move_uploaded_file($image_tmp_name, $image_folder);
         // move_uploaded_file($video_tmp_name, $video_folder);

         $insert_product = $conn->prepare("INSERT INTO `courses`(name, price, image, description, opening_day, study_time, id_cate, admin_id ) VALUES(?,?,?,?,?,?,?,?)");
         $insert_product->execute([$name, $price, $image, $description, $opening_day, $study_time, $id_cate, $admin_id]);

         echo '<script>alert("Tạo khóa học thành công!");</script>';   
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
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Thêm khóa học</h1>
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
                      <div class="form-group" >
                        <label for="exampleInputName1">Tên khóa học</label>
                        <input type="text" name="name" class="form-control" placeholder="Tên khóa học" required>
                      </div>
                  </div>
                  <div class = "col-md-6">
                    <div class="form-group">
                      <label for="exampleSelectBorder">Danh mục</label>
                      <select class="custom-select form-control-border" name="id_cate" id="exampleSelectBorder">
                        <?php
                              $select_courses = $conn->prepare("SELECT * FROM `category` ORDER BY id_cate ASC");
                              $select_courses->execute();
                              while($fetch_courses = $select_courses->fetch(PDO::FETCH_ASSOC)){ ?>
                                  <option value=<?php echo $fetch_courses['id_cate'] ?>><?php echo $fetch_courses['name_cate']; ?></option>
                          <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class = "row">
                  <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Giá</label>
                        <input type="text" name="price" class="form-control" placeholder="Giá" required>
                    </div>
                  </div>
                  <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Hình ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/jpg, image/jpeg, image/png, image/webp" required>
                    </div>
                  </div>  
                </div>
                  <div class="form-group" >
                    <label for="exampleInputName1">Mô tả</label>
                    <textarea name="description" id="editor" class="form-control" ></textarea>
                    <!-- <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script> -->
    <script>
  CKEDITOR.replace('editor');
</script>
                </div>
                <div class = "row">
                  <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Ngày khai giảng</label>
                        <input type="date" name="opening_day" class="form-control" placeholder="Ngày khai giảng" required>
                    </div>
                    </div>
                    <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Thời gian học</label>
                        <input type="text" name="study_time" class="form-control" placeholder="Thời gian học" required>
                    </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "add_course" class="btn btn-primary">Thêm</button>
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
