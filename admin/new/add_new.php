<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_new'])){

   $subject = $_POST['subject'];
   $subject = filter_var($subject, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../../uploaded_img/news/'.$image;

   $content = $_POST['content'];

   $select_new = $conn->prepare("SELECT * FROM `news` WHERE subject = ? AND content = ? " );
   $select_new->execute([$subject]);

        if($select_new->rowCount() > 0){
            echo '<script>alert("Tin tức đã tồn tại!");</script>';   
        }else{
            if($image_size > 2000000){
                echo '<script>alert("Kích thước ảnh không thích hợp!");</script>';   
            }else{
                move_uploaded_file($image_tmp_name, $image_folder);
        
         $insert_new = $conn->prepare("INSERT INTO `news`(subject, image, content, admin_id) VALUES(?, ?, ?)");
         $insert_new->execute([$subject, $image, $content, $admin_id]);

         echo '<script>alert("Tạo tin tức thành công!");</script>';   
      }
   }
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
    <section class="content-header" >
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
              <form id="quickForm" action="" method="POST" required>
                <div class="card-body">
                <div class="form-group" >
                        <label for="exampleInputName1">Hình ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/jpg, image/jpeg, image/png, image/webp" required>
                  </div> 
                  <div class="form-group" >
                      <label for="exampleInputName1">Chủ đề</label>
                      <textarea name="subject" id="post_subject" class="form-control" ></textarea>
                    <script>
                      CKEDITOR.replace('post_subject');
                    </script>                  
                  </div>
                <div class="form-group" >
                    <label for="exampleInputName1">Mô tả</label>
                    <textarea name="content" id="post_content" class="form-control" ></textarea>
                  <script>
                    CKEDITOR.replace('post_content');
                  </script>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "add_new" class="btn btn-primary">Thêm</button>
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
