
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
   <script src="js/jquery-1.11.1.min.js"></script>
   <script src="js/bootstrap.min.js"></script>
   <script src="js/bootstrap-table.js"></script>

</head>
<header class="header">
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
									echo  $fetch_profile['name'];
								?>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li>
									<a href="register_admin.php">
										<svg class="glyph stroked male-user">
											<use xlink:href="#stroked-male-user"></use>
										</svg> Đăng kí
									</a>
								</li>
								<li>
									<a href="admin_logout.php">
										<svg class="glyph stroked cancel">
											<use xlink:href="#stroked-cancel"></use>
										</svg> Đăng xuất
									</a>
								</li>
							</ul>
						</li>
					</ul>
			</div>
		</div>
	</nav>
</header>
