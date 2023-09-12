<?php

include '../components/connect.php';

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
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   $delete_recipe = $conn->prepare("DELETE FROM `recipe` WHERE id = ?");
   $delete_recipe->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:recipe.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Công thức</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin.css">
	<script type = "text/javascript" src= "ckeditor_4.21.0_full/ckeditor/ckeditor.js"></script>


</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- add products section starts  -->
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
<?php include '../components/sidebar.php' ?>

<div class = "row">
            <div id="toolbar" class="btn-group">
                  <a href="add_recipe.php" class="btn btn-success" style = "height: 3.5rem;">
                     <i class="glyphicon glyphicon-plus"></i> Thêm danh mục
                  </a>
            </div>
         </div>
         <div class="row">
               <ol class="breadcrumb">
                  <li><a href="#"><svg class="glyph stroked home">
                           <use xlink:href="#stroked-home"></use>
                           </svg></a></li>
                  <li class="active">Trang Công thức</li>
               </ol>
         </div>
         <h1 class="heading">Công thức</h1>

         <div class="box-container">
            <div class="row">
               <div class="col-md-12">
                  <div class="panel panel-default">
                     <div class="panel-body">
                        <table data-toolbar="#toolbar" data-toggle="table">
                           <thead>
                              <tr>
                                 <th data-field="id" data-sortable="true">STT</th>
                                 <th>Tên Công thức </th>
                                 <th>Danh mục</th>
                                 <th>Cách làm</th>
                                 <th>Hình ảnh</th>
                                 <th>Hành động</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              //
                              if(isset($_GET['page'])){
                                 $page=$_GET['page'];
                              }else{$page=1;}
                              $row_per_page = 10;
                              $per_page = $page * $row_per_page - $row_per_page;

                              // Thay thế cách tính $total_row bằng PDO
                              $select_account = $conn->prepare("SELECT * FROM `recipe`");
                              $select_account->execute();
                              $total_row = $select_account->rowCount();

                              $total_page = ceil($total_row / $row_per_page);
                              $list_page = " ";

                              //// previous page
                              $prv_page=$page-1;
                              if($prv_page<1){
                                 $prv_page=1;
                              }
                              $list_page.='<li class="page-item"><a class="page-link" href="recipe.php?page='.$prv_page.'">&laquo;</a></li>';
                              // for($i=1;$i<=$total_page;$i++){
                              // 	$list_page.='<li class="page-item"><a class="page-link" href="index.php?page_layout=category&page='.$i.'">'.$i.'</a></li>';
                              // }
                              // in dam so trang hien tai
                              if (!isset($_GET['page'])) {
                                 for ($i = 1; $i <= $total_page; $i++) {
                                    if ($i == 1) {
                                       $list_page .= '<li class="active"><a class="page-link" href="recipe.php?page='.$i.'">'.$i.'</a></li>';
                                    }
                                    for ($i = 2; $i <= $total_page; $i++) {
                                       $list_page .= '<li class="page-item"><a class="page-link" href="recipe.php?page='.$i.'">'.$i.'</a></li>';
                                    }
                                 }
                              } else {
                                 for ($i = 1; $i <= $total_page; $i++) {
                                          if ($i == $_GET['page']) {
                                             $list_page .= '<li class="active"><a class="page-link" href="recipe.php?page='.$i.'">'.$i.'</a></li>';
                                          }
                                          if ($i != $_GET['page']) {
                                             $list_page .= '<li class="page-item"><a class="page-link" href="recipe.php?page='.$i.'">'.$i.'</a></li>';
                                          }
                                       }
                                    }
                                    //page next
                                    $next_page=$page+1;
                                    if($next_page>$total_page){
                                       $next_page=$total_page;
                                    }
                                    $list_page.='<li class="page-item"><a class="page-link" href="recipe.php?page='.$next_page.'">&raquo;</a></li>';
                                    // Thay thế truy vấn SELECT từ MySQLi sang PDO
                                    $select_account = $conn->prepare("SELECT * FROM `recipe` INNER JOIN category ON recipe.id_cate = category.id_cate ORDER BY recipe.id DESC LIMIT :per_page OFFSET :offset");
                                    $select_account->bindValue(':per_page', $row_per_page, PDO::PARAM_INT);
                                    $select_account->bindValue(':offset', $per_page, PDO::PARAM_INT);
                                    $select_account->execute();

                                    // Kiểm tra nếu có dữ liệu trả về
                                    if ($select_account->rowCount() > 0) {
                                       // Duyệt và xử lý dữ liệu
                                       while ($fetch_products = $select_account->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                    <tr>
                                       <td style=""><?php echo $fetch_products['id'];?></td>
                                       <td style=""><?php echo $fetch_products['name'];?></td>
                                       <td style=""><?php echo $fetch_products['name_cate'];?></td>
                                       <td style="overflow-wrap: break-word;"><?php echo $fetch_products['making'];?></td>
                                       <td>
                                          <img style = "heigth: 20rem; width: 20rem;" 
                                          src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                                       </td>
                                       <td class="form-group">

                                             <a href="update_recipe.php?update=<?= $fetch_products['id']; ?>" class="btn btn-primary" style = "height: 3.5rem; width: 10rem;">Cập nhật</a>
                                             <a href="recipe.php?delete=<?= $fetch_products['id']; ?>" class="btn btn-danger"style = "height: 3.5rem; width: 10rem;" onclick="return confirm('Xóa tài khoản');">Xóa</a>
                                             </td>
                                    </tr>
                                    <?php
                                    }
                                 }
                                    ?>
                           </tbody>
                        </table>
                     </div>
                     <div class="panel-footer">
                        <nav aria-label="Page navigation example">
                           <ul class="pagination">
                              
                              <?php echo $list_page;?>
                              
                           </ul>
                        </nav>
                     </div>
               </div>
            </div>
         </div>
   </div>
<!-- show products section ends -->










<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>
<!-- <script src="../js/jquery-1.11.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script> -->
</body>
</html>