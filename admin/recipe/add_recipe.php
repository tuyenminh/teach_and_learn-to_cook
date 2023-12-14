<?php
use Carbon\Carbon;
use Carbon\CarbonInterval;
require('../../carbon/autoload.php');
include '../../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['add_recipe'])) {

   $id_cate = $_POST['id_cate'];
   $id_cate = filter_var($id_cate, FILTER_SANITIZE_STRING);
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $video = $_POST['video'];
   $video = filter_var($video, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../../uploaded_img/' . $image;

   $making = $_POST['making'];

   $time = $_POST['time'];
   $time = filter_var($time, FILTER_SANITIZE_STRING);
   $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

   $select_recipes = $conn->prepare("SELECT * FROM `recipe` WHERE name = ?");
   $select_recipes->execute([$name]);

   if ($select_recipes->rowCount() > 0) {
      $message[] = 'Công thức đã tồn tại!';
   } else {
      if ($image_size > 2000000) {
         $message[] = 'Kích thước ảnh không thích hợp';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);

         $insert_recipe = $conn->prepare("INSERT INTO `recipe`(name, image, making, time, video, regis_date, id_cate, admin_id ) VALUES(?,?,?,?,?,?,?,?)");
         $insert_recipe->execute([$name, $image, $making, $time, $video, $now, $id_cate, $admin_id]);
         // Lấy id của công thức vừa thêm
         $recipe_id = $conn->lastInsertId();

         // Xử lý và thêm chi tiết nguyên liệu vào bảng `detail_ingre`
$ingredient_names = $_POST['ingre_name'];
$unit_names = $_POST['unit_name'];
$numbers = $_POST['number'];

for ($i = 0; $i < count($ingredient_names); $i++) {
    $ingre_name = filter_var($ingredient_names[$i], FILTER_SANITIZE_STRING);
    $unit_name = filter_var($unit_names[$i], FILTER_SANITIZE_STRING);
    $unit_number = filter_var($numbers[$i], FILTER_SANITIZE_STRING);

    // Kiểm tra xem đơn vị đã tồn tại trong bảng `unit` chưa
    $select_unit = $conn->prepare("SELECT id FROM `unit` WHERE unit_name = ? AND number = ?");
    $select_unit->execute([$unit_name, $unit_number]);

    if ($select_unit->rowCount() > 0) {
        // Nếu đơn vị đã tồn tại, lấy id đơn vị
        $unit_id = $select_unit->fetch(PDO::FETCH_ASSOC)['id'];
    } else {
        // Nếu đơn vị chưa tồn tại, thêm mới và lấy id
        $insert_unit = $conn->prepare("INSERT INTO `unit` (unit_name, number) VALUES (?, ?) ON DUPLICATE KEY UPDATE number = number + ?");
        $insert_unit->execute([$unit_name, $unit_number, $unit_number]);
        $unit_id = $conn->lastInsertId();
    }

    // Kiểm tra xem nguyên liệu đã tồn tại trong bảng `ingre` chưa
    $select_ingredient = $conn->prepare("SELECT id FROM `ingre` WHERE ingre_name = ?");
    $select_ingredient->execute([$ingre_name]);

    if ($select_ingredient->rowCount() > 0) {
        // Nếu nguyên liệu đã tồn tại, lấy id nguyên liệu
        $ingre_id = $select_ingredient->fetch(PDO::FETCH_ASSOC)['id'];
    } else {
        // Nếu nguyên liệu chưa tồn tại, thêm mới và lấy id
        $insert_ingredient = $conn->prepare("INSERT IGNORE INTO `ingre` (ingre_name) VALUES (?)");
        $insert_ingredient->execute([$ingre_name]);
        $ingre_id = $conn->lastInsertId();
    }

    // Thêm chi tiết nguyên liệu vào bảng `detail_ingre`
    $insert_detail_ingre = $conn->prepare("INSERT INTO `detail_ingre` (recipe_id, unit_id, ingre_id) VALUES (?, ?, ?)");
    $insert_detail_ingre->execute([$recipe_id, $unit_id, $ingre_id]);
}

  echo '<script>alert("Thêm công thức thành công!");</script>';   
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
            <h1>Thêm công thức</h1>
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
                        <label for="exampleInputName1">Tên công thức</label>
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
                        <textarea name="making" id="content_making" class="form-control" ></textarea>
                    </div>
                </div>
                <div class="row">
                  <div class="form-group" id="ingredient-list">
                      <label for="exampleInputName1">Nguyên liệu</label>
                      <div class="ingredient-entry" style="margin-bottom: 10px;
                  margin-top: 10px;" data-index="1">
                          <div class="row">
                              <div class="col-md-1">
                                  <span class="ingredient-index">1</span>
                              </div>
                              <div class="col-md-3">
                                  <input type="text" name="ingre_name[]" class="form-control" placeholder="Tên nguyên liệu" required>
                              </div>
                              <div class="col-md-3">
                                  <input type="text" name="unit_name[]" class="form-control" placeholder="Tên đơn vị" required>
                              </div>
                              <div class="col-md-3">
                                  <input type="number" name="number[]" class="form-control" placeholder="Số lượng đơn vị" required>
                              </div>
                              <div class="col-md-2">
                                  <button type="button" class="btn btn-success btn-add">+</button>
                                  <button type="button" class="btn btn-danger btn-remove">-</button>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
                    <div class="form-group" >
                        <label for="exampleInputName1">Link Video</label>
                        <input type="text" name="video" class="form-control" placeholder="Link video" required>
                    </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name = "add_recipe" class="btn btn-primary">Thêm</button>
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
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ingredientList = document.getElementById("ingredient-list");
    let ingredientCount = 1;

    function addIngredientEntry() {
        const ingredientEntry = ingredientList.querySelector(".ingredient-entry").cloneNode(true);
        const currentIndex = ingredientCount + 1;

        // Increment the ingredient count
        ingredientCount++;

        // Update the index number in the new entry
        const indexSpan = ingredientEntry.querySelector(".ingredient-index");
        indexSpan.textContent = currentIndex;

        // Clear input values in the new entry
        const inputs = ingredientEntry.querySelectorAll("input");
        inputs.forEach(input => input.value = "");

        // Attach event listeners to the new buttons
        const btnAdd = ingredientEntry.querySelector(".btn-add");
        btnAdd.addEventListener("click", addIngredientEntry);

        const btnRemove = ingredientEntry.querySelector(".btn-remove");
        btnRemove.addEventListener("click", function () {
            // Check if it's not the first entry before removing
            if (currentIndex > 1) {
                ingredientList.removeChild(ingredientEntry);
                // Reorder the indices after removing an entry
                reorderIndices();
            }
        });

        ingredientList.appendChild(ingredientEntry);

        // Reorder the indices after adding a new entry
        reorderIndices();
    }

    // Attach initial event listener
    const btnAdd = ingredientList.querySelector(".btn-add");
    btnAdd.addEventListener("click", addIngredientEntry);

    // Attach event listeners to existing remove buttons
    const btnRemoveList = ingredientList.querySelectorAll(".btn-remove");
    btnRemoveList.forEach(btnRemove => {
        btnRemove.addEventListener("click", function () {
            const ingredientEntry = btnRemove.closest(".ingredient-entry");
            // Check if it's not the first entry before removing
            if (ingredientEntry.getAttribute("data-index") > 1) {
                ingredientList.removeChild(ingredientEntry);
                // Reorder the indices after removing an entry
                reorderIndices();
            }
        });
    });

    // Function to reorder the indices
    function reorderIndices() {
        const indexSpans = ingredientList.querySelectorAll(".ingredient-entry .ingredient-index");
        indexSpans.forEach((indexSpan, index) => {
            indexSpan.textContent = index + 1;
        });
    }
});
</script>
</body>
</html>
