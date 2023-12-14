<?php
use Carbon\Carbon;
use Carbon\CarbonInterval;
require('../../carbon/autoload.php');
include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:admin_login.php');
};

if(isset($_POST['update_recipe'])) {
    $recipe_id = $_POST['pid'];

    // Xử lý cập nhật thông tin công thức
    $name = $_POST['name'];
    $id_cate = $_POST['id_cate'];

    $video = $_POST['video'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $making = $_POST['making'];
    $time = $_POST['time'];

    // Kiểm tra và xử lý cập nhật thông tin công thức
    if (empty($name) || empty($video) || empty($making) || empty($time)) {
        $error_message = 'Vui lòng điền đầy đủ thông tin.';
        echo '<script>alert("' . $error_message . '");</script>';
        exit();
    }

    // Kiểm tra kích thước ảnh
    if ($image_size > 2000000) {
        $error_message = 'Kích thước ảnh không được vượt quá 2MB.';
        echo '<script>alert("' . $error_message . '");</script>';
        exit();
    }

    // Xử lý cập nhật hoặc thêm mới ảnh
    if (!empty($image)) {
        $allowed_formats = array('jpg', 'jpeg', 'png', 'webp');
        $uploaded_image_info = pathinfo($image);
        $uploaded_image_extension = strtolower($uploaded_image_info['extension']);

        if (!in_array($uploaded_image_extension, $allowed_formats)) {
            $error_message = 'Định dạng ảnh không hợp lệ. Chỉ chấp nhận các định dạng: JPG, JPEG, PNG, WEBP.';
            echo '<script>alert("' . $error_message . '");</script>';
            exit();
        }
        
        $image_folder = '../../uploaded_img/' . $image;
        move_uploaded_file($image_tmp_name, $image_folder);

        // Cập nhật thông tin công thức với ảnh mới
        $update_recipe = $conn->prepare("UPDATE `recipe` SET name = ?, id_cate = ?, video = ?, image = ?, making = ?, time = ?, admin_id = ?  WHERE id = ?");
        $update_recipe->execute([$name, $id_cate, $video, $image, $making, $time, $admin_id, $recipe_id]);
    } else {
        // Cập nhật thông tin công thức không thay đổi ảnh
        $update_recipe = $conn->prepare("UPDATE `recipe` SET name = ?, id_cate = ?, video = ?, making = ?, time = ?, admin_id = ? WHERE id = ?");
        $update_recipe->execute([$name, $id_cate, $video, $making, $time, $admin_id, $recipe_id]);
    }

    // Kiểm tra xem cập nhật có thành công hay không
    if ($update_recipe->rowCount() > 0) {
        // Cập nhật thông tin công thức thành công

        // Xử lý cập nhật hoặc thêm mới nguyên liệu
        $ingredients = $_POST['ingre_name'];
        $units = $_POST['unit_name'];
        $numbers = $_POST['number'];

       // Xử lý cập nhật hoặc thêm mới nguyên liệu
$ingredients = $_POST['ingre_name'];
$units = $_POST['unit_name'];
$numbers = $_POST['number'];

for ($i = 0; $i < count($ingredients); $i++) {
  $ingredient_name = filter_var($ingredients[$i], FILTER_SANITIZE_STRING);
  $unit_name = filter_var($units[$i], FILTER_SANITIZE_STRING);
  $number = filter_var($numbers[$i], FILTER_SANITIZE_STRING);

  // Kiểm tra xem nguyên liệu đã tồn tại trong CSDL chưa
  $select_ingredient = $conn->prepare("SELECT id FROM `ingre` WHERE ingre_name = ?");
  $select_ingredient->execute([$ingredient_name]);
  $ingredient_id = ($select_ingredient->rowCount() > 0) ? $select_ingredient->fetch(PDO::FETCH_ASSOC)['id'] : null;

  if (!$ingredient_id) {
      // Nếu nguyên liệu chưa tồn tại, thêm mới
      $insert_ingredient = $conn->prepare("INSERT INTO `ingre` (ingre_name) VALUES (?)");
      $insert_ingredient->execute([$ingredient_name]);
      $ingredient_id = $conn->lastInsertId();
  }
    // Lấy hoặc thêm mới đơn vị
    $select_unit = $conn->prepare("SELECT id FROM `unit` WHERE unit_name = ? AND number = ?");
    $select_unit->execute([$unit_name, $number]);
    $unit_id = ($select_unit->rowCount() > 0) ? $select_unit->fetch(PDO::FETCH_ASSOC)['id'] : null;

    if (!$unit_id) {
        // Nếu đơn vị chưa tồn tại, thêm mới
        $insert_unit = $conn->prepare("INSERT INTO `unit` (unit_name, number) VALUES (?, ?) ON DUPLICATE KEY UPDATE number = number + ?");
        $insert_unit->execute([$unit_name, $number, $number]);
        $unit_id = $conn->lastInsertId();
    }

   // Sử dụng $ingredient_id và $unit_id để cập nhật hoặc thêm mới chi tiết nguyên liệu
$select_detail_ingre = $conn->prepare("SELECT * FROM `detail_ingre` WHERE recipe_id = ? AND ingre_id = ?");
$select_detail_ingre->execute([$recipe_id, $ingredient_id]);

if ($select_detail_ingre->rowCount() > 0) {
    // Nếu có dòng dữ liệu thì kiểm tra xem có sự thay đổi ở `unit_id` hay không
    $existing_row = $select_detail_ingre->fetch(PDO::FETCH_ASSOC);
    
    if ($existing_row['unit_id'] != $unit_id) {
        // Nếu có sự thay đổi ở `unit_id`, cập nhật dòng dữ liệu hiện tại
        $update_detail_ingre = $conn->prepare("UPDATE `detail_ingre` SET unit_id = ? WHERE recipe_id = ? AND ingre_id = ?");
        $update_detail_ingre->execute([$unit_id, $recipe_id, $ingredient_id]);
    }
} else {
    // Nếu không có dòng dữ liệu, thêm mới chi tiết nguyên liệu vào bảng `detail_ingre`
    $insert_detail_ingre = $conn->prepare("INSERT INTO `detail_ingre` (recipe_id, unit_id, ingre_id) VALUES (?, ?, ?)");
    $insert_detail_ingre->execute([$recipe_id, $unit_id, $ingredient_id]);
}
}


        echo '<script>alert("Cập nhật công thức thành công!");</script>';
    } else {
        $error_message = 'Không thể cập nhật thông tin công thức. Vui lòng thử lại sau.';
        echo '<script>alert("' . $error_message . '");</script>';
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
            <h1>Cập nhật công thức</h1>
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
                $update_id = $_GET['update_recipe'];
                $show_recipe = $conn->prepare("SELECT * FROM `recipe` WHERE id = ?");
                $show_recipe->execute([$update_id]);
               // Kiểm tra xem công thức có tồn tại hay không
if ($show_recipe->rowCount() > 0) {
  // Lấy thông tin công thức
  $fetch_recipe = $show_recipe->fetch(PDO::FETCH_ASSOC);

  // Thực hiện truy vấn để lấy danh sách nguyên liệu cho công thức
  $select_ingredients = $conn->prepare("SELECT ingre_name, unit_name, number FROM `detail_ingre` di
      JOIN `ingre` i ON di.ingre_id = i.id
      JOIN `unit` u ON di.unit_id = u.id
      WHERE di.recipe_id = ?");
  $select_ingredients->execute([$update_id]);
  $ingredients = $select_ingredients->fetchAll(PDO::FETCH_ASSOC);
?>
              <form id="quickForm" action="" method="POST" enctype="multipart/form-data"required>
                <input type="hidden" name="pid" value="<?= $fetch_recipe['id']; ?>">
                <input type="hidden" name="old_image" value="<?= $fetch_recipe['image']; ?>">
                <div class="card-body">
                <div class = "row">
                  <div class = "col-md-6">
                      <div class="form-group" >
                        <label for="exampleInputName1">Tên công thức</label>
                        <input type="text" name="name" class="form-control" value="<?= $fetch_recipe['name']; ?>" placeholder="Tên công thức" required>
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
                        <label for="exampleInputName1">Thời gian nấu</label>
                        <input type="text" name="time" class="form-control" value="<?= $fetch_recipe['time']; ?>" placeholder="Thời gian" required>
                    </div>
                  </div>
                  <div class = "col-md-6">
                    <div class="form-group" >
                        <label for="exampleInputName1">Hình ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/jpg, image/jpeg, image/png, image/webp" >
                    </div>
                  </div>  
                </div>
                <div class = "row">
                    <div class="form-group" >
                        <label for="exampleInputName1">Cách làm</label>
                        <textarea name="making" id="content_making" class="form-control" ><?= $fetch_recipe['making']; ?>" </textarea>
                    </div>
                </div>
                <div class="row">
                  <div class="ingredient-list">
                  <label for="exampleInputName1">Nguyên liệu</label>

                    <?php
                    // Lặp qua mảng $ingredients để hiển thị dữ liệu
                    foreach ($ingredients as $index => $ingredient) :
                    ?>
                        <div class="ingredient-entry" style="margin-bottom: 10px;
                  margin-top: 10px;" data-index="<?= $index + 1 ?>" data-id="<?= $ingredient['detail_id'] ?>">
                          <div class="row">
                            <div class="col-md-1">
                              <!-- <span class="ingredient-index">1</span> -->
                            </div>
                            <div class="col-md-3">
                              <input type="text" name="ingre_name[]" class="form-control" value="<?= $ingredient['ingre_name'] ?>" placeholder="Tên nguyên liệu" required>
                            </div>
                            <div class="col-md-3">
                              <input type="text" name="unit_name[]" class="form-control" value="<?= $ingredient['unit_name'] ?>" placeholder="Tên đơn vị" required>
                            </div>
                            <div class="col-md-3">
                              <input type="number" name="number[]" class="form-control" value="<?= $ingredient['number'] ?>" placeholder="Số lượng đơn vị" required>
                            </div>
                            <!-- Thêm nút xóa nếu không phải dòng đầu tiên -->
                            <?php if ($index > 0) : ?>
                              <div class="col-md-2">
                                <button type="button" class="btn btn-success btn-add">+</button>
                                <button type="button" class="btn btn-danger btn-remove">-</button>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const ingredientList = document.querySelector(".ingredient-list");

    // Hàm kiểm tra sự trùng lặp
    function checkDuplicates() {
        const ingredientEntries = ingredientList.querySelectorAll(".ingredient-entry");
        const uniqueEntries = new Set();
        let hasDuplicates = false;

        ingredientEntries.forEach(entry => {
            const key = entry.querySelector("[name='ingre_name[]']").value +
                        entry.querySelector("[name='unit_name[]']").value +
                        entry.querySelector("[name='number[]']").value;

            if (uniqueEntries.has(key)) {
                hasDuplicates = true;
                return;
            }

            uniqueEntries.add(key);
        });

        if (hasDuplicates) {
            console.error("Form contains duplicate entries!");
        }
    }

    // Sự kiện thêm mới cho tất cả các nút cộng
    const btnAddList = document.querySelectorAll(".btn-add");
    btnAddList.forEach(btnAdd => {
        btnAdd.addEventListener("click", function () {
            const ingredientEntry = btnAdd.closest('.ingredient-list').querySelector(".ingredient-entry").cloneNode(true);

            // Làm sạch giá trị của input trong dòng mới
            const inputs = ingredientEntry.querySelectorAll("input");
            inputs.forEach(input => input.value = "");

            // Sự kiện xóa dòng mới
            const btnRemove = ingredientEntry.querySelector(".btn-remove");
            btnRemove.addEventListener("click", function () {
                ingredientList.removeChild(ingredientEntry);
                checkDuplicates();
            });

            ingredientList.appendChild(ingredientEntry);
            checkDuplicates();
        });
    });

    // Sự kiện xóa dòng đã có
    ingredientList.addEventListener("click", function (event) {
        const target = event.target;
        if (target.classList.contains("btn-remove")) {
            const ingredientEntry = target.closest(".ingredient-entry");
            if (ingredientEntry.dataset.index > 1) {
                ingredientList.removeChild(ingredientEntry);
                checkDuplicates();
            }
        }
    });

    // Kiểm tra sự trùng lặp khi trang được tải
    checkDuplicates();
});


</script>

                    <div class="form-group" >
                        <label for="exampleInputName1">Link Video</label>
                        <input type="text" name="video" class="form-control" value="<?= $fetch_recipe['video']; ?>" placeholder="Link video" required>
                    </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "update_recipe" class="btn btn-primary">Cập nhật</button>
                  <a href="list_recipe.php" class="btn btn-primary">Trở về</a>

                </div>
              </form>
              <?php
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
