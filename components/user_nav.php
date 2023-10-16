<nav class="main-menu">
							<ul>
								<li class="current-list-item"><a href="index.php">Trang chủ</a>
								</li>
								<li><a href="recipe.php">Công thức nấu ăn</a></li>
								<li><a href="news.php">Tin tức</a></li>
								<li><a href="contacts.php">Liên hệ</a></li>
								<li>
									<div class="header-icons">
									<?php
										$count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
										$count_cart_items->execute([$user_id]);
										$total_cart_items = $count_cart_items->rowCount();
									?>
									<style>
										.shopping-cart {
											position: relative; 
											text-decoration: none; 
										}
											.shopping-cart span {
											position: absolute; 
											top: -10px; 
											right: -10px; 
											background-color: #F28123; 
											color: white; 
											border-radius: 50%; 
											padding: 5px 10px; 
											font-size: 14px; 
											}
									</style>
										<a class="shopping-cart" href="cart.php"><i class="fas fa-shopping-cart"></i><?php if ($user_id) { ?><span>(<?= $total_cart_items; ?>)</span><?php } ?></a>
										<a class="mobile-hide search-bar-icon" href="#"><i class="fas fa-search"></i></a>
										<?php
										$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
										$select_profile->execute([$user_id]);
										if ($select_profile->rowCount() > 0) {
											$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

											?>
												<a href="profile.php" class="btn">
												<?php
													$name = $fetch_profile['name'];
													$spacePosition = strpos($name, ' ');

													if ($spacePosition !== false) {
														// Tên có dấu cách, hiển thị họ
														$lastName = substr(strrchr($name, ' '), 1);
														echo '<a href="profile.php" class="btn">' . $lastName . '</a>';
													} else {
														// Tên không có dấu cách, hiển thị icon user
														echo '<a href="profile.php" class="btn"><i class="fas fa-user"></i></a>';
													}
													?>

												</a>
												<a style = "font-size: 15px; "href="components/user_logout.php" onclick="return confirm('Đăng xuất khỏi trang web này?');" class="delete-btn">| Đăng xuất</a>
											<?php
										} else {
											?>
											<a style="font-size: 15px;" href="login.php">Đăng nhập</a>
											<a style="font-size: 15px;" href="register.php">| Đăng ký</a>
											<?php
										}
										?>
											
									</div>
								</li>
							</ul>
						</nav>