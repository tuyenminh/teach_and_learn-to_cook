<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
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
	<script src="./js/lumino.glyphs.js"></script>
	<script type = "text/javascript" src= "ckeditor/ckeditor.js"></script>
</head>
<body>
<?php include '../components/admin_header.php' ?>

<?php include '../components/sidebar.php' ?>

	<!--/.sidebar-->
	<!-- master page -->
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
							$select_users = $conn->prepare("SELECT * FROM `courses`");
							$select_users->execute();
							$numbers_of_users = $select_users->rowCount();
							echo $numbers_of_users;
      					?>
					</div>
						<div class="text-muted">Khóa học</div>
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
								$select_users = $conn->prepare("SELECT * FROM `recipe`");
								$select_users->execute();
								$numbers_of_users = $select_users->rowCount();
								echo $numbers_of_users;
							?>					
						</div>
						<div class="text-muted">Công thức</div>
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
						<div class="text-muted">Tài khoản</div>
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
						<div class="large">25.2k</div>
						<div class="text-muted">Quảng Cáo</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--/.row-->
</div>
<!--/.main-->

<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap-table.js"></script>

</body>

</html>