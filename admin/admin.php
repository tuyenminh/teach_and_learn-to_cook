<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}
?>
<?php
// thống kê biểu đồ tròn khóa học
$select_courses = $conn->prepare("SELECT `category`.*, COUNT(courses.id_cate) AS 'number_courses' 
FROM courses INNER JOIN `category` ON courses.id_cate = category.id_cate GROUP BY courses.id_cate");
$select_courses->execute();
$data = [];

while ($fetch_courses = $select_courses->fetch(PDO::FETCH_ASSOC)){ 
    $data[] = $fetch_courses; 
}

$select_courses = $conn->prepare("SELECT `category`.*, COUNT(recipe.id_cate) AS 'number_recipes' 
FROM recipe INNER JOIN `category` ON recipe.id_cate = category.id_cate GROUP BY recipe.id_cate");
$select_courses->execute();
$data1 = [];

while ($fetch_courses = $select_courses->fetch(PDO::FETCH_ASSOC)){ 
    $data1[] = $fetch_courses; 
}
// Bước 1: Truy vấn danh sách các năm có trong cơ sở dữ liệu
$year_query = $conn->prepare("SELECT DISTINCT YEAR(regis_date) AS year FROM receipt");
$year_query->execute();

$years = array();
while ($row = $year_query->fetch(PDO::FETCH_ASSOC)) {
    $years[] = $row['year'];
}

// Bước 2: Chọn năm cần hiển thị (ví dụ: năm 2022)
$selected_year = 2021; // Thay thế bằng năm bạn muốn

// Bước 3: Sử dụng năm đã chọn để truy vấn dữ liệu
$select_total_sales = $conn->prepare("SELECT DATE_FORMAT(regis_date, '%Y-%m-%d') AS date, SUM(total_price) AS total_price
                                     FROM receipt
                                     WHERE YEAR(regis_date) = :selected_year
                                     GROUP BY date
                                     ORDER BY date");

$select_total_sales->bindParam(':selected_year', $selected_year, PDO::PARAM_INT);
$select_total_sales->execute();

// Bước 4: Xử lý dữ liệu
$data_sales = array();
while ($fetch_total_sales = $select_total_sales->fetch(PDO::FETCH_ASSOC)) { 
    $data_sales[] = array($fetch_total_sales['date'], (float)$fetch_total_sales['total_price']); 
}

   		?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CookingFood Admin</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css"></head>
<body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
</head>
<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Trang chủ</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->


  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <span class="brand-text font-weight-light">CookingFood ADMIN</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
        <?php
									$select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
									$select_profile->execute([$admin_id]);
									$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
									
								?>
                <p style = "color: white;"> <?php echo  $fetch_profile['name'];?></p>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="http://localhost/teach_and_learn-to_cook/admin/admin.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Tài khoản
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Quản trị</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/accounts/add_admin.php" class="nav-link">
                  <p>Thêm quản trị</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/accounts/list_admin.php" class="nav-link">
                  <p>Danh sách quản trị</p>
                </a>
              </li>
            </ul>
            </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Khách hàng</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/accounts/add_customer.php" class="nav-link">
                  <p>Thêm khách hàng</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/accounts/list_customer.php" class="nav-link">
                  <p>Danh sách khách hàng</p>
                </a>
              </li>
            </ul>
            </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Danh mục
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/category/add_category.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Thêm danh mục</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/category/list_category.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách danh mục</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                Khóa học
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/course/add_course.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Thêm khóa học</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/course/list_course.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách khóa học</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Công thức
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/recipe/add_recipe.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Thêm công thức</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/recipe/list_recipe.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách công thức</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Liên hệ
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/tables/simple.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách liên hệ</p>
                </a>
              </li>
            </ul>
          </li>
        
        </ul>
      </nav>      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
			  	<?php
                	$select_users = $conn->prepare("SELECT * FROM `users`");
                    $select_users->execute();
                    $numbers_of_users = $select_users->rowCount();
                ?>	
                <h3><?php echo $numbers_of_users;?></h3>

                <p>Khách hàng</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
			  <?php
                              $select_users = $conn->prepare("SELECT * FROM `receipt`");
                              $select_users->execute();
                              $numbers_of_users = $select_users->rowCount();
                           ?>
                <h3><?php echo $numbers_of_users;?><sup style="font-size: 20px"></sup></h3>

                <p>Đơn hàng</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">Chi tiết<i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
			  <?php
                           $select_users = $conn->prepare("SELECT * FROM `messages`");
                           $select_users->execute();
                           $numbers_of_users = $select_users->rowCount();
							   ?>	
                <h3><?php echo $numbers_of_users;?></h3>

                <p>Tin nhắn</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">Chi tiếttiết<i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
			  <?php
                              $total_completes = 0;
                              $select_completes = $conn->prepare("SELECT * FROM `receipt` WHERE pay_status = ?");
                              $select_completes->execute(['Đã hoàn thành']);
                              while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                                 $total_completes += $fetch_completes['total_price'];
                              }
                           ?>
                <h3><?php echo number_format(($total_completes)). " VNĐ";
                           ?></h3>

                <p>Doanh thu</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">Chi tiết<i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-7 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Đơn hàng
                </h3>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart"
                       style="position: relative; height: 300px;">
					   <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">Mã</th>
                      <th>Khóa học</th>
                      <th>Giá</th>
                      <th>Trạng thái</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
                              $select_orders = $conn->prepare("SELECT * FROM `receipt` ORDER BY id ASC LIMIT 3");
                              $select_orders->execute();
                              if($select_orders->rowCount() > 0){
                                 while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
                           ?>
                            <tr>
								<td><?php echo $fetch_orders['id'];?></td>
                                <td style = "width: 200px; "><?php echo $fetch_orders['total_course'];?></td>
                                <td><?php echo number_format($fetch_orders['total_price'], 0, ',', '.') . " VNĐ" ;?></td>
                                <td>
                                    <?php if ($fetch_orders['pay_status'] == "Đã hoàn thành"){ ?>
                                          <span class = "badge bg-success"> Đẫ hoàn thành</span>  
                                       <?php            
                                          } if ($fetch_orders['pay_status'] == "Đang xử lý") {
                                             ?> <span class="badge bg-warning">Đang xử lý</span>
                                          <?php }
                                          ?>
                                </td>
                            </tr>
                            <?php
                                 }
                              }
                              ?>    
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->

            </div>
            <!-- /.card -->

                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-5 connectedSortable">

            <!-- Map card -->
            <div class="card bg-gradient-primary">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-map-marker-alt mr-1"></i>
                  Biểu đồ số lượng
                </h3>
              </div>
              <div class="card-body" style = "background: #fff;">
                <div id="piechart" style = "
                            width: 300px; 
                            height: 300px;">
                </div> 
					      <!-- <div id="piechart_recipe" style = "
                            width: 200px; height: 200px;"> -->
                </div> 
			        </div>
					
            </div>
          </div>
            <!-- /.card -->
          </section>
          <!-- right col -->
          <!-- Bước 1: Tạo dropdown để chọn năm -->

          <!-- <div id="curve_chart" style="width: 100%; height: 500px"></div> -->
          <div class="row">
                              <?php
include 'admin1.php';


                              ?>
          </div>
         

        </div>
        <!-- /.row (main row) -->

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="../plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="../plugins/raphael/raphael.min.js"></script>
<script src="../plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="../plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard2.js"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['name_cate', 'number_courses'],

         <?php
		 	foreach ($data as $key){
			echo "['".$key['name_cate']."', ".$key['number_courses']."],";
			} 
		 ?>
        ]);

      var options = {
        colors: ['#608da2', '#1dadc0', '#779eb2', '#446879'],
        legend: 'none',
        pieSliceText: 'label',
        // title: 'Thống kê khóa học',
        pieStartAngle: 100,
		pieHole: 0.4,
		chartArea: {
			left: 10, // Điều chỉnh khoảng cách từ lề trái
			top: 10, // Điều chỉnh khoảng cách từ lề trên
			right: 10, // Điều chỉnh khoảng cách từ lề phải
			bottom: 10, // Điều chỉnh khoảng cách từ lề dưới
		},

        
      };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['name_cate', 'number_courses'],

         <?php
		 	foreach ($data1 as $key){
			echo "['".$key['name_cate']."', ".$key['number_recipes']."],";
			} 
		 ?>
        ]);

      var options = {
        colors: ['#608da2', '#1dadc0', '#779eb2', '#446879'],
        legend: 'none',
        pieSliceText: 'label',
        title: 'Thống kê công thức',
        pieStartAngle: 100,
      };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_recipe'));
    
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Khởi tạo biến dataPHP
            var dataPHP = [];

            <?php
            if(isset($_POST["year"])) {
                $selectedYear = $_POST["year"];
                $query = "SELECT MONTH(regis_date) AS month, SUM(total_price) AS revenue
                          FROM receipt
                          WHERE YEAR(regis_date) = :year
                          GROUP BY MONTH(regis_date)";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":year", $selectedYear, PDO::PARAM_INT);
                $stmt->execute();

                $data = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $data[] = [$row['month'], (int)$row['revenue']];
                }

                // Chuyển dữ liệu sang định dạng JSON
                echo "dataPHP = " . json_encode($data) . ";";
            }
            ?>

            // Tạo dữ liệu cho biểu đồ
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Tháng');
            data.addColumn('number', 'Doanh thu');
            data.addRows(dataPHP);

            // Tùy chọn của biểu đồ
            var options = {
                title: 'Doanh thu hàng tháng',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            // Vẽ biểu đồ
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
        }
    </script>
</body>
</html>
