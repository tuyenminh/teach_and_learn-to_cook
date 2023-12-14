<?php
use Carbon\Carbon;
use Carbon\CarbonInterval;
require('carbon/autoload.php');

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
};
$grand_total = 0;
$cart_items[] = '';
$select_cart = $conn->prepare("SELECT SUM(courses.price) AS total_price
                            FROM `registration_form`
                            INNER JOIN courses ON registration_form.course_id = courses.id
                            WHERE user_id = ? AND registration_form.status = 'Chưa thanh toán'");
$select_cart->execute([$user_id]);
if($select_cart->rowCount() > 0){
    $fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC);
    $grand_total = $fetch_cart['total_price'];
}

function execPostRequest($url, $data)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data))
		);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		//execute post
		$result = curl_exec($ch);
		//close connection
		curl_close($ch);
		return $result;
	}

if(isset($_POST['payUrl'])){
   
			$cart_items = $conn->prepare("SELECT registration_form.id AS regis_form_id, courses.price AS courses_price
				FROM registration_form
				INNER JOIN courses ON registration_form.course_id = courses.id
				WHERE registration_form.user_id = ? AND registration_form.status = 'Chưa thanh toán'");
			$cart_items->execute([$user_id]);

			while ($cart_item = $cart_items->fetch(PDO::FETCH_ASSOC)) {
			$regis_form_id = $cart_item['regis_form_id'];
			$now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

			// Kiểm tra xem hóa đơn đã tồn tại trong cơ sở dữ liệu chưa
			$check_order = $conn->prepare("SELECT * FROM `receipts` WHERE receipt_date = ? AND regis_form_id = ?");
			$check_order->execute([$now, $regis_form_id]);

			if ($check_order->rowCount() == 0) {
			// Chèn dữ liệu vào bảng `receipts`
			$insert_order = $conn->prepare("INSERT INTO `receipts`(receipt_date, regis_form_id, total ) VALUES(?, ?,?)");
			$insert_order->execute([$now, $regis_form_id, $grand_total]);

			// Cập nhật trạng thái trong bảng `registration_form` thành "Đã thanh toán"
			$update_status = $conn->prepare("UPDATE `registration_form` SET status = 'Đã thanh toán' WHERE id = ?");
			$update_status->execute([$regis_form_id]);

			// Kiểm tra việc chèn dữ liệu vào bảng receipts
			if ($insert_order->rowCount() > 0 && $update_status->rowCount() > 0) {
			echo '<script>alert("Thanh toán thành công");</script>';
			} 
			} else {
			echo '<script>alert("Đơn hàng đã tồn tại");</script>';
			}
			}

            // Tiếp tục với thanh toán Momo
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $partnerCode = 'MOMOBKUN20180529';
			$accessKey = 'klm05TvNBzhg7h7j';
			$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
			$orderInfo = 'Thanh toán qua momo';
			$amount = $grand_total;
			$orderId = time() ."";
			$redirectUrl = "http://localhost/teach_and_learn-to_cook/cart.php";
			$ipnUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
			$extraData = "";
	
			$partnerCode = $partnerCode;
			$accessKey = $accessKey;
			$secretKey = $secretKey;
			$orderId = $orderId; // Mã đơn hàng
			$orderInfo = $orderInfo;
			$amount = $amount;
			$ipnUrl = $ipnUrl;
			$redirectUrl = $redirectUrl;
			$extraData = $extraData;
	
			$requestId = time() . "";
			$requestType = "payWithATM";
			// $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
			//before sign HMAC SHA256 signature
			$rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
			$signature = hash_hmac("sha256", $rawHash, $secretKey);
			$data = array('partnerCode' => $partnerCode,
				'partnerName' => "Test",
				"storeId" => "MomoTestStore",
				'requestId' => $requestId,
				'amount' => $amount,
				'orderId' => $orderId,
				'orderInfo' => $orderInfo,
				'redirectUrl' => $redirectUrl,
				'ipnUrl' => $ipnUrl,
				'lang' => 'vi',
				'extraData' => $extraData,
				'requestType' => $requestType,
				'signature' => $signature);
	//execPostRequest(kiểu string). json_encode biến kiểu thành json để phù hợp dữ liệu
			$result = execPostRequest($endpoint, json_encode($data));
			$jsonResult = json_decode($result, true);  // decode json
	
			//Just a example, please check more in there
            header('Location: ' . $jsonResult['payUrl']);
		

}
if (isset($_GET['partnerCode'])) {
    if (
        isset($_GET['partnerCode']) &&
        isset($_GET['orderId']) &&
        isset($_GET['amount']) &&
        isset($_GET['orderType']) &&
        isset($_GET['transId']) &&
        isset($_GET['payType']) &&
        isset($_GET['signature'])
    ) {
        $code_order = rand(0, 9999);
        $partnerCode = filter_var($_GET['partnerCode'], FILTER_SANITIZE_STRING);
        $orderId = filter_var($_GET['orderId'], FILTER_SANITIZE_STRING);
        $amount = filter_var($_GET['amount'], FILTER_SANITIZE_STRING);
        $orderType = filter_var($_GET['orderType'], FILTER_SANITIZE_STRING);
        $transId = filter_var($_GET['transId'], FILTER_SANITIZE_STRING);
        $payType = filter_var($_GET['payType'], FILTER_SANITIZE_STRING);
        $signature = filter_var($_GET['signature'], FILTER_SANITIZE_STRING);

        // Chèn dữ liệu vào bảng `momo`
        $insert_momo = $conn->prepare("INSERT INTO `momo`(partnerCode, orderId, amount, orderType, transId, payType, signature, code_cart) VALUES(?,?,?,?,?,?,?,?)");
        $insert_momo->execute([$partnerCode, $orderId, $amount, $orderType, $transId, $payType, $signature, $code_order]);

		
			
		}
	
}
// if (isset($_POST['payUrl'])) {
//     // Lặp qua từng sản phẩm trong giỏ hàng
//     $cart_items = $conn->prepare("SELECT registration_form.id AS regis_form_id, courses.price AS courses_price
//                                 FROM registration_form
//                                 INNER JOIN courses ON registration_form.course_id = courses.id
//                                 WHERE registration_form.user_id = ? AND registration_form.status = 'Chưa thanh toán'");
//     $cart_items->execute([$user_id]);

//     while ($cart_item = $cart_items->fetch(PDO::FETCH_ASSOC)) {
//         $regis_form_id = $cart_item['regis_form_id'];
//         $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

//         // Kiểm tra xem hóa đơn đã tồn tại trong cơ sở dữ liệu chưa
//         $check_order = $conn->prepare("SELECT * FROM `receipts` WHERE receipt_date = ? AND regis_form_id = ?");
//         $check_order->execute([$now, $regis_form_id]);

//         if ($check_order->rowCount() == 0) {
//             // Chèn dữ liệu vào bảng `receipts`
//             $insert_order = $conn->prepare("INSERT INTO `receipts`(receipt_date, regis_form_id, total ) VALUES(?, ?,?)");
//             $insert_order->execute([$now, $regis_form_id, $grand_total]);

//             // Cập nhật trạng thái trong bảng `registration_form` thành "Đã thanh toán"
//             $update_status = $conn->prepare("UPDATE `registration_form` SET status = 'Đã thanh toán' WHERE id = ?");
//             $update_status->execute([$regis_form_id]);

//             // Kiểm tra việc chèn dữ liệu vào bảng receipts
//             if ($insert_order->rowCount() > 0 && $update_status->rowCount() > 0) {
//                 echo '<script>alert("Thanh toán thành công");</script>';
//             } else {
//                 echo '<script>alert("Thanh toán không thành công");</script>';
//             }
//         } else {
//             echo '<script>alert("Đơn hàng đã tồn tại");</script>';
//         }
//     }
// }




?>
<!DOCTYPE html>
<html lang="en">
<?php include 'components/user_head.php'; ?>
<body>
	
	<!--PreLoader-->
    <div class="loader">
        <div class="loader-inner">
            <div class="circle"></div>
        </div>
    </div>
    <!--PreLoader Ends-->
	
	<!-- header -->
	<div class="top-header-area" id="sticker">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-sm-12 text-center">
					<div class="main-menu-wrap">
						<!-- logo -->
						<div class="site-logo">
							<a href="index.html">
                            <img style="width: 100%;" src="fruitkha-1.0.0/fruitkha-1.0.0/assets/img/logo.png" alt="">
							</a>
						</div>
						<!-- logo -->

						<!-- menu start -->
                        <?php include 'components/user_nav.php'; ?>
						<a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
						<div class="mobile-menu"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end header -->

	<!-- search area -->
	<div class="search-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<span class="close-btn"><i class="fas fa-window-close"></i></span>
					<div class="search-bar">
						<div class="search-bar-tablecell">
							<h3>Search For:</h3>
							<input type="text" placeholder="Keywords">
							<button type="submit">Search <i class="fas fa-search"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end search arewa -->
	
	<!-- breadcrumb-section -->
	<div class="breadcrumb-section breadcrumb-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="breadcrumb-text">
						<p>Khóa học bổ ích cho mọi người</p>
						<h1>Thanh toán</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- check out section -->
	<div class="checkout-section mt-150 mb-150">
		<div class="container">
		<!-- <form action="" method="post" target="_blank" enctype="application/x-www-form-urlencode"> -->
		<form action="checkout.php" method="post" >
			<div class="row">
				<div class="col-lg-8">
					<div class="checkout-accordion-wrap">
						<div class="accordion" id="accordionExample">
						  <div class="card single-accordion">
						    <div class="card-header" id="headingOne">
						      <h5 class="mb-0">
						        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						          Thông tin khách hàng
						        </button>
						      </h5>
						    </div>

						    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
						      <div class="card-body">
						        <div class="billing-address-form">
                                
						        	
                                        <span style= "font-size: 15px;"><strong>
                                            Tên tài khoản
                                        </strong></span>
						        		<p><input style= "width:100%; border: 1px solid #ccc; padding: 10px;" type="text" name="name" value="<?= $fetch_profile['name'] ?>"placeholder="Tên học viên" readonly></p>
                                        <span style= "font-size: 15px;"><strong>
                                            Email
                                        </strong></span>

						        		<p><input style= "width:100%; border: 1px solid #ccc; padding: 10px;" type="email" name="email" value="<?= $fetch_profile['email'] ?>" placeholder="Email" readonly></p>
                                        <span style= "font-size: 15px;"><strong>
                                            Địa chỉ
                                        </strong></span>

						        		<p><input style= "width:100%; border: 1px solid #ccc; padding: 10px;" type="text" name="address" value="<?= $fetch_profile['address'] ?>" placeholder="Địa chỉ" readonly></p>
                                        <span style= "font-size: 15px;"><strong>
                                            Số điện thoại
                                        </strong></span>

						        		<p><input style= "width:100%; border: 1px solid #ccc; padding: 10px;" type="tel" name="number" value="<?= $fetch_profile['number'] ?>" placeholder="Số điện thoại" readonly></p>
						        </div>
                                <div style ="padding-left:20px;">
                                    <a href="update_profile.php" class="boxed-btn">Cập nhật thông tin</a>
                                </div>
						      </div>
						    </div>
						  </div>
						</div>

					</div>
				</div>

				<div class="col-lg-4">
					<div class="order-details-wrap">
						<table class="order-details">
							<thead>
                           
								<tr>
									<th>Khóa học</th>
									<th>Giá</th>
								</tr>
                                
							</thead>
							<tbody class="checkout-details">
                            <?php
                                    $grand_total = 0;
                                    $cart_items[] = '';
                                    $select_cart = $conn->prepare("SELECT registration_form.status AS regis_status, registration_form.id AS regis_form_id, courses.name AS courses_name, courses.price AS courses_price, users.*
									FROM registration_form
									INNER JOIN courses ON registration_form.course_id = courses.id
									INNER JOIN users ON registration_form.user_id = users.id
									WHERE registration_form.user_id = ?");
                                    $select_cart->execute([$user_id]);

									if ($select_cart->rowCount() > 0) {
										while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
											if ($fetch_cart['regis_status'] == 'Chưa thanh toán') {
												// Hiển thị thông tin khóa học từ bảng courses
												$total_course = implode($cart_items);
												$grand_total += $fetch_cart['courses_price'];
												?>
												<input type="hidden" name="regis_form_id" value="<?= $fetch_cart['regis_form_id']; ?>">
												<tr>
													<td style="font-size: 15px;"><?= $fetch_cart['courses_name']; ?></td>
													<td style="font-size: 15px;"><?= number_format($fetch_cart['courses_price']) . " VNĐ"; ?></td>
												</tr>
												<?php
											}
										}
									} else {
										echo '<p style="color: red;" class="empty">Giỏ hàng trống!</p>';
									}
                                ?>
                                <tr class="total-data">
									<td><strong>Tổng tiền: </strong></td>
									<td><?php echo number_format($grand_total). " VNĐ"; ?></td>
								</tr>
							</tbody>
						</table>
                        <a href="cart.php" class="boxed-btn">GIỎ HÀNG</a>			
						<button style="font-family: 'Poppins', sans-serif;
										display: inline-block;
										background-color: transparent; /* Đặt nền trong suốt */
										color: #F28123; /* Màu chữ */
										font-weight: normal;
										font-size: 1.5rem;
										padding: 10px 15px;
										border: 2px solid #F28123; /* Viền có độ rộng 2px và màu trùng với màu chữ */
										border-radius: 50px; /* Đặt độ cong để nút trở nên tròn */
										text-align: center;
										text-decoration: none;
										cursor: pointer;" 
						class="boxed-btn" type="submit" name="payUrl">Thanh toán
							
						</button>	
					</div>
				</div>
			</div>
		<form>
			
		</div>
	</div>
	<!-- end check out section -->

	<!-- footer -->
	<?php include 'components/user_footer.php'; ?>
</body>
</html>