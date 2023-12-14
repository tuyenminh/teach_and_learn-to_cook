<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_about'])){

    $subject = $_POST['subject'];

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../../uploaded_img/news/'.$image;


   $content = $_POST['content'];

   $select_products = $conn->prepare("SELECT * FROM `news` WHERE subject = ? AND subject = ? AND content = ?");
   $select_products->execute([$subject, $image, $content]);

   if($select_products->rowCount() > 0){
    echo '<script>alert("Tin tức đã tồn tại!");</script>';   
}else{
      if($image_size > 2000000){
        echo '<script>alert("Kích thước ảnh không thích hợp!");</script>';   
    }else{
         move_uploaded_file($image_tmp_name, $image_folder);
         // move_uploaded_file($video_tmp_name, $video_folder);

         $insert_product = $conn->prepare("INSERT INTO `news`(subject, image, content, admin_id ) VALUES(?,?,?,?)");
         $insert_product->execute([$subject, $image, $content, $admin_id]);

         echo '<script>alert("Tạo tin tức thành công!");</script>';   
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
            <h1>Thêm tin tức</h1>
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
                        <label for="exampleInputName1">Chủ đề</label>
                        <input type="text" name="subject" class="form-control" placeholder="Chủ đề" required>
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
                    <label for="exampleInputName1">Nội dung</label>
                    <textarea name="content" id="editor_news" class="form-control" ></textarea>
    <script>
  CKEDITOR.replace('editor_news');
</script>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "add_about" class="btn btn-primary">Thêm</button>
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
