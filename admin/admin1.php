<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}


$select_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
$select_contents->execute([$admin_id]);
$total_contents = $select_contents->rowCount();

$select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
$select_playlists->execute([$admin_id]);
$total_playlists = $select_playlists->rowCount();

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
$select_likes->execute([$admin_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
$select_comments->execute([$admin_id]);
$total_comments = $select_comments->rowCount();

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
	<script src="js/lumino.glyphs.js"></script>
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
                            <?php
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
    
         ?>
							<?=  ?>
							<span class="caret"></span>
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
		<?php
			if (isset($_GET['page_layout'])) {
				$row = $_GET['page_layout']; ?>
				<li <?php if ($_GET['page_layout'] == "dashboard") { ?> class="active" <?php } ?>>
					<a href="index.php?page_layout=dashboard">
						<svg class="glyph stroked dashboard-dial">
							<use xlink:href="#stroked-dashboard-dial"></use>
						</svg> Dashboard
					</a>
					</li>
				<?php if (defined("admin")) { ?>
					<li <?php if ($_GET['page_layout'] == "user" || $_GET['page_layout'] == "add_user" || $_GET['page_layout'] == "edit_user") { ?> class="active" <?php } ?>>
						<a href="index.php?page_layout=user">
							<svg class="glyph stroked male user ">
								<use xlink:href="#stroked-male-user" />
							</svg>Quản lý thành viên
						</a>
					</li>
				<?php } ?>
				<li <?php if ($_GET['page_layout'] == "category" || $_GET['page_layout'] == "add_category" || $_GET['page_layout'] == "edit_category") { ?> class="active" <?php } ?>>
					<a href="index.php?page_layout=category">
						<svg class="glyph stroked open folder">
							<use xlink:href="#stroked-open-folder" />
						</svg>Loại khóa học
					</a>
				</li>
				<li <?php if ($_GET['page_layout'] == "loaikhoahoc" || $_GET['page_layout'] == "add_loaikhoahoc" || $_GET['page_layout'] == "edit_loaikhoahoc") { ?> class="active" <?php } ?>>
					<a href="index.php?page_layout=loaicongthuc">
						<svg class="glyph stroked open folder">
							<use xlink:href="#stroked-open-folder" />
						</svg>Loại công thức
					</a>
				</li>
				<li <?php if ($_GET['page_layout'] == "product" || $_GET['page_layout'] == "add_product" || $_GET['page_layout'] == "edit_product") { ?> class="active" <?php } ?>>
					<a href="index.php?page_layout=product">
						<svg class="glyph stroked bag">
							<use xlink:href="#stroked-bag"></use>
						</svg>Khóa học
					</a>
				</li>
			
				<li <?php if ($_GET['page_layout'] == "congthuc" || $_GET['page_layout'] == "add_congthuc" || $_GET['page_layout'] == "edit_congthuc") { ?> class="active" <?php } ?>>
					<a href="index.php?page_layout=congthuc">
						<svg class="glyph stroked bag">
							<use xlink:href="#stroked-bag"></use>
						</svg>Công thức
					</a>
				</li>
				<li <?php if ($_GET['page_layout'] == "comment") { ?> class="active" <?php } ?>>
					<a href="index.php?page_layout=comment">
						<svg class="glyph stroked two messages">
							<use xlink:href="#stroked-two-messages" />
						</svg> Quản lý bình luận</a>
				</li>
				<li <?php if ($_GET['page_layout'] == "") { ?> class="active" <?php } ?>>
					<a href="ads.html">
						<svg class="glyph stroked chain">
							<use xlink:href="#stroked-chain" />
						</svg> Quản lý quảng cáo
					</a>
				</li>
				<li <?php if ($_GET['page_layout'] == "setting" || $_GET['page_layout'] == "setting_title" || $_GET['page_layout'] == "add_title" || $_GET['page_layout'] == "edit_title" || $_GET['page_layout'] == "setting_footer" || $_GET['page_layout'] == "add_footer1" || $_GET['page_layout'] == "edit_footer1" || $_GET['page_layout'] == "number_product" || $_GET['page_layout'] == "number_comment" || $_GET['page_layout'] == "logo") { ?> class="active" <?php } ?>>
					<a href="index.php?page_layout=setting">
						<svg class="glyph stroked gear">
							<use xlink:href="#stroked-gear" />
						</svg> Cấu hình
					</a>
				</li>
			<?php } else { ?>
				<li class="active">
					<a href="index.php?page_layout=dashboard">
						<svg class="glyph stroked dashboard-dial">
							<use xlink:href="#stroked-dashboard-dial"></use>
						</svg> Dashboard
					</a>
				</li>
				<?php if (defined("admin")) { ?>
					<li>
						<a href="index.php?page_layout=user">
							<svg class="glyph stroked male user ">
								<use xlink:href="#stroked-male-user" />
							</svg>Tài khoản
						</a>
					</li>
				<?php } ?>
				<li>
					<a href="index.php?page_layout=category">
						<svg class="glyph stroked open folder">
							<use xlink:href="#stroked-open-folder" />
						</svg>Loại khóa học
					</a>
				</li>
				<li>
					<a href="index.php?page_layout=loaicongthuc">
						<svg class="glyph stroked bag">
							<use xlink:href="#stroked-bag"></use>
						</svg>Loại công thức
					</a>
				</li>
				<li>
					<a href="index.php?page_layout=product">
						<svg class="glyph stroked bag">
							<use xlink:href="#stroked-bag"></use>
						</svg>Quản lý sản phẩm
					</a>
				</li>
				<li>
					<a href="index.php?page_layout=comment">
						<svg class="glyph stroked two messages">
							<use xlink:href="#stroked-two-messages" />
						</svg> Quản lý bình luận</a>
				</li>
				<li>
					<a href="ads.html">
						<svg class="glyph stroked chain">
							<use xlink:href="#stroked-chain" />
						</svg> Quản lý quảng cáo
					</a>
				</li>
				<li>
					<a href="index.php?page_layout=setting">
						<svg class="glyph stroked gear">
							<use xlink:href="#stroked-gear" />
						</svg> Cấu hình
					</a>
				</li>
			<?php } ?>
		</ul>

	</div>
	<!--/.sidebar-->
	<!-- master page -->
	<?php
	if (isset($_GET['page_layout'])) {
		switch ($_GET['page_layout']) {
// Loại khóa học
			case 'category':
				include_once('modules/category/category.php');
				break;
			case 'add_category':
				include_once('modules/category/add_category.php');
				break;
			case 'edit_category':
				include_once('modules/category/edit_category.php');
				break;
//Loại công thức
			case 'loaicongthuc':
				include_once('modules/loaicongthuc/loaicongthuc.php');
				break;
			case 'add_loaicongthuc':
				include_once('modules/loaicongthuc/add_loaicongthuc.php');
				break;
			case 'edit_loaicongthuc':
				include_once('modules/loaicongthuc/edit_loaicongthuc.php');
				break;
//Khóa học		
				case 'product':
					include_once('modules/product/product.php');
					break;
				case 'add_product':
					include_once('modules/product/add_product.php');
					break;
				case 'edit_product':
					include_once('modules/product/edit_product.php');
					break;
//Công thức
			case 'congthuc':
				include_once('modules/congthuc/congthuc.php');
				break;
			case 'add_congthuc':
				include_once('modules/congthuc/add_congthuc.php');
				break;
			case 'edit_congthuc':
				include_once('modules/congthuc/edit_congthuc.php');
				break;		
//Tài khoản
			case 'user':
				include_once('modules/user/user.php');
				break;
			case 'add_user':
				include_once('modules/user/add_user.php');
				break;
			case 'edit_user':
				include_once('modules/user/edit_user.php');
				break;
//Thống kê
			case 'dashboard':
				include_once('dashboard.php');
				break;
			case "account":
				include_once("modules/account/account.php");
				break;
			case "setting_footer":
				include_once("modules/footer/setting_footer.php");
				break;
			case "setting":
				include_once("modules/setting/setting.php");
				break;
			case "add_footer1":
				include_once("modules/footer/add_footer1.php");
				break;
			case "edit_footer1":
				include_once("modules/footer/edit_footer1.php");
				break;
			case "setting_title":
				include_once("modules/title/setting_title.php");
				break;
			case "add_title":
				include_once("modules/title/add_title.php");
				break;
			case "edit_title":
				include_once("modules/title/edit_title.php");
				break;
			case "number_product":
				include_once("modules/setting/number_product.php");
				break;
			case "number_comment":
				include_once("modules/setting/number_comment.php");
				break;
			case "logo":
				include_once("modules/setting/logo.php");
				break;
		}
	} else {
		include_once('dashboard.php');
	}

	?>

</body>

</html>