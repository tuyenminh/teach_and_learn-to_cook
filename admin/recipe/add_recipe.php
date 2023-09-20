<?php

include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_recipe'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $video = $_POST['video'];
   $video = filter_var($video, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../../uploaded_img/'.$image;

   $making = $_POST['making'];
   $making = filter_var($making, FILTER_SANITIZE_STRING);

   $time = $_POST['time'];
   $time = filter_var($time, FILTER_SANITIZE_STRING); 

   $select_recipes = $conn->prepare("SELECT * FROM `recipe` WHERE name = ?");
   $select_recipes->execute([$name]);

   if($select_recipes->rowCount() > 0){
      $message[] = 'Công thức đã tồn tại!';
   }else{
      if($image_size > 2000000){
         $message[] = 'Kích thước ảnh không thích hợp';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);

         $insert_recipe = $conn->prepare("INSERT INTO `recipe`(name, category, image, making, time, video ) VALUES(?,?,?,?,?,?)");
         $insert_recipe->execute([$name, $category, $image, $making, $video, $time, ]);

         $message[] = 'Thêm công thức mới thành công!';
      }

   }


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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Thêm công thức</h1>
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
                        <input type="text" name="name" class="form-control" placeholder="Tên công thức" required>
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
                        <label for="exampleInputName1">Thời gian nấu</label>
                        <input type="text" name="time" class="form-control" placeholder="Thời gian" required>
                    </div>
                  </div>
                  <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Hình ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/jpg, image/jpeg, image/png, image/webp" required>
                    </div>
                  </div>  
                </div>
                <div class = "row">
                    <div class="form-group" >
                        <label for="exampleInputName1">Cách làm</label>
                        <textarea name="making" id="post_content" class="form-control" row = "3" ></textarea>
                    </div>
                </div>
                <div class = "row">
                    <div class="form-group" >
                        <label for="exampleInputName1">Nguyên liệu</label>
                        <textarea name="ingre" id="post_content1" class="form-control" row = "3" ></textarea>
                    </div>
                </div>
                    <div class="form-group" >
                        <label for="exampleInputName1">Link Video</label>
                        <input type="text" name="video" class="form-control" placeholder="Link video" required>
                    </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "add_recipe" class="btn btn-primary">Tạo</button>
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
