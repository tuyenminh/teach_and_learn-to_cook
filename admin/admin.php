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
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d', strtotime('-1 year'));
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');

$query_daily_revenue = "SELECT DATE(receipt_date) AS date, SUM(total) AS daily_revenue
                        FROM receipts
                        WHERE receipt_date BETWEEN :start_date AND :end_date
                        GROUP BY DATE(receipt_date)
                        ORDER BY date ASC";

$result_daily_revenue = $conn->prepare($query_daily_revenue);
$result_daily_revenue->bindParam(':start_date', $start_date);
$result_daily_revenue->bindParam(':end_date', $end_date);
$result_daily_revenue->execute();

$data_daily_revenue = [];
while ($row_daily_revenue = $result_daily_revenue->fetch(PDO::FETCH_ASSOC)) {
    $date = $row_daily_revenue['date'];
    $daily_revenue = (float) $row_daily_revenue['daily_revenue'];
    $data_daily_revenue[] = [$date, $daily_revenue];
}

// Truy vấn cơ sở dữ liệu để lấy dữ liệu tổng hợp doanh thu qua các năm
$query_revenue = "SELECT YEAR(receipt_date) AS year, SUM(total) AS revenue
          FROM receipts
          GROUP BY YEAR(receipt_date)
          ORDER BY year ASC";
$result_revenue = $conn->query($query_revenue);

// Tạo một mảng chứa dữ liệu doanh thu
$data_revenue= [];
while ($row_revenue = $result_revenue->fetch(PDO::FETCH_ASSOC)) {
    $year = $row_revenue['year'];
    $revenue = (float) $row_revenue['revenue'];
    $data_revenue[] = [$year, $revenue];
}
   		?>
<!DOCTYPE html>
<html lang="en">

<?php include '../components/admin_head.php'; ?>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">


  <!-- Navbar -->
  <?php include '../components/admin_nav.php'; ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include '../components/admin_sidebar.php'; ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
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
                              $select_users = $conn->prepare("SELECT * FROM `receipts`");
                              $select_users->execute();
                              $numbers_of_users = $select_users->rowCount();
                           ?>
                <h3><?php echo $numbers_of_users;?><sup style="font-size: 20px"></sup></h3>

                <p>Đăng ký học</p>
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
                           $select_users = $conn->prepare("SELECT * FROM `message`");
                           $select_users->execute();
                           $numbers_of_users = $select_users->rowCount();
							   ?>	
                <h3><?php echo $numbers_of_users;?></h3>

                <p>Tin nhắn</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">Chi tiết<i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
			  <?php
                              $total_completes = 0;
                              $select_completes = $conn->prepare("SELECT * FROM `receipts` INNER JOIN registration_form ON receipts.regis_form_id= registration_form.id
                              WHERE status = ?");
                              $select_completes->execute(['Đã thanh toán']);
                              while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                                 $total_completes += $fetch_completes['total'];
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
                  Đăng ký học mới nhất
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
                              $select_orders = $conn->prepare("SELECT receipts.id AS id, courses.name AS courses_name, registration_form.regis_day AS registration_form_regis_day, registration_form.status AS registration_form_status FROM `receipts` INNER JOIN registration_form ON receipts.regis_form_id= registration_form.id
                              INNER JOIN courses ON registration_form.course_id= courses.id
                              ORDER BY receipts.id ASC LIMIT 4");
                              $select_orders->execute();
                              if($select_orders->rowCount() > 0){
                                 while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
                           ?>
                            <tr>
								                <td><?php echo $fetch_orders['id'];?></td>
                                <td style = "width: 200px; "><?php echo $fetch_orders['courses_name'];?></td>
                                <td style = "width: 200px; "><?php echo $fetch_orders['registration_form_regis_day'];?></td>
                                <td>
                                    <?php if ($fetch_orders['registration_form_status'] == "Đã thanh toán"){ ?>
                                          <span class = "badge bg-success"> Đẫ thanh toán</span>  
                                       <?php            
                                          } if ($fetch_orders['registration_form_status'] == "Chưa thanh toán") {
                                             ?> <span class="badge bg-warning">Chưa thanh toán</span>
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
                  Thống kê lượt đăng ký khóa học
                </h3>
              </div>
              <div class="card-body" style = "background: #fff;">
                <div id="piechart" style = "
                            width: 300px; 
                            height: 300px;">
                </div> 
                </div> 
			        </div>
					
            </div>
          </div>
            <!-- /.card -->
          </section>
          <section class="col-lg-12 connectedSortable" style = "padding-right: 15px; padding-left: 15px;">
            <!-- Map card -->
            <div class="card bg-gradient-primary">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-map-marker-alt mr-1"></i>
                  Tổng thống kê doanh thu 
                </h3>
              </div>
              <div class="card-body" style = "background: #fff;">
              <div id="revenue_chart" style="width: 900px; height: 500px;"></div>


            </div>
            </div>
            <!-- /.card -->
            </section>
          <section class="col-lg-12 connectedSortable" style = "padding-right: 15px; padding-left: 15px;">
            <!-- Map card -->
            <div class="card bg-gradient-primary">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-map-marker-alt mr-1"></i>
                  Thống kê doanh thu theo từng thời điểm
                </h3>
              </div>
              <div class="card-body" style = "background: #fff;">

                <?php include'annual_statistics.php';
                ?>


            </div>
            </div>
            <!-- /.card -->
            </section>
           
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
</div>
<!-- ./wrapper -->
<?php include '../components/admin_footer.php'; ?>


</body>
</html>
