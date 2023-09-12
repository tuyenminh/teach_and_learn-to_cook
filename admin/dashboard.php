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
   		?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CookingFood Admin</title>

  <link rel="stylesheet" href="  https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.js">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- jsGrid -->
  <link rel="stylesheet" href="../plugins/jsgrid/jsgrid.min.css">
  <link rel="stylesheet" href="../plugins/jsgrid/jsgrid-theme.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <?php include('../components/navbar.php'); ?>
  <!-- /.navbar -->
  <!-- Main Sidebar Container -->
    <!-- Sidebar -->
    <?php include('../components/sidebar.php'); ?>
  <!-- Content Wrapper. Contains page content -->
    <!-- Main content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
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
                              $select_orders = $conn->prepare("SELECT * FROM `receipt` ORDER BY id ASC LIMIT 5");
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
											width: 400px; 
											height: 300px;">
					</div> 
					<!-- <div id="piechart_recipe" style = "width: 200px; height: 200px;"> -->
                </div> 
			</div>
					
              </div>
            </div>
            <!-- /.card -->
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jsGrid -->
<script src="../plugins/jsgrid/demos/db.js"></script>
<script src="../plugins/jsgrid/jsgrid.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- Page specific script -->
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
</body>
</html>
