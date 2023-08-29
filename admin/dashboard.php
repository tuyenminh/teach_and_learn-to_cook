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
$connect = new mysqli('localhost', 'root', '', 'food');
$query = "SELECT `category`.*, COUNT(courses.id_cate) AS 'number_courses' FROM courses INNER JOIN `category` ON courses.id_cate = category.id GROUP BY courses.id_cate";
$result = mysqli_query($connect, $query);
$data = [];

while ($row = mysqli_fetch_array($result)) {
    $data[] = $row; // Thêm dòng này để thêm dữ liệu vào mảng $data
}
// thống kê biểu đồ tròn công thức
$query1 = "SELECT `category`.*, COUNT(recipe.id_cate) AS 'number_recipes' FROM recipe INNER JOIN `category` ON recipe.id_cate = category.id GROUP BY recipe.id_cate";
$result1 = mysqli_query($connect, $query1);
$data1 = [];

while ($row = mysqli_fetch_array($result1)) {
    $data1[] = $row; // Thêm dòng này để thêm dữ liệu vào mảng $data
}
   		?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/bootstrap-table.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<!--Icons-->
	<!-- link bootrap -->
	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>  -->

	<script src="./js/lumino.glyphs.js"></script>
	<script type = "text/javascript" src= "ckeditor/ckeditor.js"></script>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

</head>
<body>
<?php include '../components/admin_header.php' ?>

<?php include '../components/sidebar.php' ?> 
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home">
						<use xlink:href="#stroked-home"></use>
					</svg></a></li>
			<li class="active">Trang chủ quản trị</li>
		</ol>
	</div>
	<!--/.row-->

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Trang quản trị</h1>
		</div>
	</div>
	<!--/.row-->

	<div class="row">
		<div class="col-xs-12 col-md-6 col-lg-3">
			<div class="panel panel-blue panel-widget ">
				<div class="row no-padding">
					<div class="col-sm-3 col-lg-5 widget-left">
						<svg class="glyph stroked bag">
							<use xlink:href="#stroked-bag"></use>
						</svg>
					</div>
					<div class="col-sm-9 col-lg-7 widget-right">
						<div class="large">
						<?php
							$select_users = $conn->prepare("SELECT * FROM `receipt`");
							$select_users->execute();
							$numbers_of_users = $select_users->rowCount();
							echo $numbers_of_users;
      					?>
					</div>
						<div class="text-muted">Đăng kí</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-6 col-lg-3">
			<div class="panel panel-orange panel-widget">
				<div class="row no-padding">
					<div class="col-sm-3 col-lg-5 widget-left">
						<svg class="glyph stroked empty-message">
							<use xlink:href="#stroked-empty-message"></use>
						</svg>
					</div>
					<div class="col-sm-9 col-lg-7 widget-right">
						<div class="large">
							<?php
								$select_users = $conn->prepare("SELECT * FROM `messages`");
								$select_users->execute();
								$numbers_of_users = $select_users->rowCount();
								echo $numbers_of_users;
							?>					
						</div>
						<div class="text-muted">Liên hệ</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-6 col-lg-3">
			<div class="panel panel-teal panel-widget">
				<div class="row no-padding">
					<div class="col-sm-3 col-lg-5 widget-left">
						<svg class="glyph stroked male-user">
							<use xlink:href="#stroked-male-user"></use>
						</svg>
					</div>
					<div class="col-sm-9 col-lg-7 widget-right">
					<div class="large">
							<?php
								$select_users = $conn->prepare("SELECT * FROM `users`");
								$select_users->execute();
								$numbers_of_users = $select_users->rowCount();
								echo $numbers_of_users;
							?>					
						</div>
						<div class="text-muted">Khách hàng</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-6 col-lg-3">
			<div class="panel panel-red panel-widget">
				<div class="row no-padding">
					<div class="col-sm-3 col-lg-5 widget-left">
						<svg class="glyph stroked app-window-with-content">
							<use xlink:href="#stroked-app-window-with-content"></use>
						</svg>
					</div>
					<div class="col-sm-9 col-lg-7 widget-right">
						<div class="large">
						<?php
								$select_admin = $conn->prepare("SELECT * FROM `courses`");
								$select_admin->execute();
								$numbers_of_admin = $select_admin->rowCount();
								echo $numbers_of_admin;
							?>
						</div>
						<div class="text-muted">Khóa học</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class = "row" >
		<div id="chart" style="height: 250px;padding-top: 20px; padding-bottom: 20px;"></div>

	</div>
	<div class="row">
        <div class="col-md-6">
            <div id="piechart_courses" style="height: 300px; border: 3px solid green;"></div>
        </div>
        <div class="col-md-6">
            <div id="piechart_recipe" style="height: 300px; border: 3px solid green;"></div>
        </div>
    </div>

</div>
<!--/.main-->

<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap-table.js"></script>
<script> 
new Morris.Line({
  // ID of the element in which to draw the chart.
  element: 'chart',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    { year: '2008', price: 20 },
    { year: '2009', price: 10 },
    { year: '2010', price: 5 },
    { year: '2011', price: 5 },
    { year: '2012', price: 20 }
  ],
  // The name of the data record attribute that contains x-values.
  xkey: 'year',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['price'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['Value']
});
</script>
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
          title:'Biểu đồ Khóa học ',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_courses'));
        chart.draw(data, options);
      }
    </script>
	<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['name_cate', 'number_recipes'],

         <?php
		 	foreach ($data1 as $key){
			echo "['".$key['name_cate']."', ".$key['number_recipes']."],";
			} 
		 ?>
        ]);

        var options = {
          title:'Biểu đồ công thức',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_recipe'));
        chart.draw(data, options);
      }
    </script>
</body>

</html>