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
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php"><span></span></a>
				<ul class="user-menu">
					<li class="dropdown pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<svg class="glyph stroked male-user">
								<use xlink:href="#stroked-male-user"></use>
							</svg>
							Admin							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li>
								<a href="index.php?page_layout=account">
									<svg class="glyph stroked male-user">
										<use xlink:href="#stroked-male-user"></use>
									</svg> Hồ sơ
								</a>
							</li>
							<li>
								<a href="output.php">
									<svg class="glyph stroked cancel">
										<use xlink:href="#stroked-cancel"></use>
									</svg> Đăng xuất
								</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div><!-- /.container-fluid -->
	</nav>
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<form role="search">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
		</form>
		<ul class="nav menu">
						<li class="active">
					<a href="index.php?page_layout=dashboard">
						<svg class="glyph stroked dashboard-dial">
							<use xlink:href="#stroked-dashboard-dial"></use>
						</svg> Dashboard
					</a>
				</li>
									<li>
						<a href="users_accounts.php">
							<svg class="glyph stroked male user ">
								<use xlink:href="#stroked-male-user" />
							</svg>Tài khoản
						</a>
				</li>
				<li>
					<a href="products.php">
						<svg class="glyph stroked open folder">
							<use xlink:href="#stroked-open-folder" />
						</svg>Khóa học
					</a>
				</li>
				<li>
					<a href="recipe.php">
						<svg class="glyph stroked bag">
							<use xlink:href="#stroked-bag"></use>
						</svg>Công thức
					</a>
				</li>
				<li>
					<a href="messages.php">
						<svg class="glyph stroked two messages">
							<use xlink:href="#stroked-two-messages" />
						</svg>Liên hệ </a>
				</li>
				<li>
					<a href="placed_orders.php">
						<svg class="glyph stroked chain">
							<use xlink:href="#stroked-chain" />
						</svg> Đăng kí
					</a>
				</li>
				<li>
					<a href="placed_orders.php">
						<svg class="glyph stroked gear">
							<use xlink:href="#stroked-gear" />
						</svg> Đang xử lý
					</a>
				</li>
                <li>
					<a href="placed_orders.php">
						<svg class="glyph stroked chain">
							<use xlink:href="#stroked-chain" />
						</svg> Thanh toán
					</a>
				</li>
					</ul>

	</div>
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
			<h1 class="page-header">Trang chủ quản trị</h1>
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
						<div class="large"><br />
<b>Warning</b>:  mysqli_num_rows() expects parameter 1 to be mysqli_result, bool given in <b>C:\xampp\htdocs\Web_teach_cookingFood\admin\dashboard.php</b> on line <b>35</b><br />
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
						<div class="large"><br />
<b>Warning</b>:  mysqli_num_rows() expects parameter 1 to be mysqli_result, bool given in <b>C:\xampp\htdocs\Web_teach_cookingFood\admin\dashboard.php</b> on line <b>50</b><br />
</div>
						<div class="text-muted">Bình Luận</div>
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
						<div class="large">1</div>
						<div class="text-muted">Thành Viên</div>
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
<script src="js/bootstrap.min.js"></script>

</body>

</html>