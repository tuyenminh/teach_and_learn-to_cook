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
	$select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
	$select_cart->execute([$user_id]);
	if($select_cart->rowCount() > 0){
		while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
		$cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].') - ';
		$total_course = implode($cart_items);
		$grand_total += ($fetch_cart['price']);
	
		}
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
    // Kiểm tra giỏ hàng
    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);

    if ($check_cart->rowCount() > 0) {
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
        $total_course = filter_var($_POST['total_course'], FILTER_SANITIZE_STRING);
        $total_price = filter_var($_POST['total_price'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

        // Kiểm tra xem đơn hàng đã tồn tại trong cơ sở dữ liệu chưa
        $check_order = $conn->prepare("SELECT * FROM `receipt` WHERE user_id = ? AND total_course = ? AND total_price = ?");
        $check_order->execute([$user_id, $total_course, $total_price]);

        if ($check_order->rowCount() == 0) {
            // Chèn dữ liệu vào bảng `receipt`
            $insert_order = $conn->prepare("INSERT INTO `receipt`(user_id, name, number, total_course, total_price, email, regis_date) VALUES(?,?,?,?,?,?,?)");
            $insert_order->execute([$user_id, $name, $number, $total_course, $total_price, $email, $now]);

            // Xóa giỏ hàng
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);

            // Tiếp tục với thanh toán Momo
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $partnerCode = 'MOMOBKUN20180529';
			$accessKey = 'klm05TvNBzhg7h7j';
			$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
			$orderInfo = 'Thanh toán qua momo';
			$amount = $grand_total;
			$orderId = time() ."";
			$redirectUrl = "http://localhost/teach_and_learn-to_cook/thanhtoan.php";
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
        } else {
            // Đơn hàng đã tồn tại, không thực hiện thanh toán
			echo '<script>alert("Bạn đã đăng kí khóa học này. Không thể tiếp tục!");</script>';   

        }
    } else {
        // Giỏ hàng trống, không thực hiện thanh toán
			echo '<script>alert("Giỏ hàng trống. Không thể thanh toán!");</script>';   
    }
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

        // if ($insert_momo) {
        //     $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        //     $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
        //     $total_course =  filter_var($_POST['total_course'], FILTER_SANITIZE_STRING) ;
        //     $total_price = filter_var($_POST['total_price'], FILTER_SANITIZE_STRING) ;
        //     $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
		// 	$now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

        //     $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        //     $check_cart->execute([$user_id]);

        //     if ($check_cart->rowCount() > 0) {
        //         // Chèn dữ liệu vào bảng `receipt`

		// 		$insert_order = $conn->prepare("INSERT INTO `receipt`(user_id, name, number, total_course, total_price, email, regis_date) VALUES(?,?,?,?,?,?,?)");
		// 		$insert_order->execute([$user_id, $name, $number,  $total_course, $total_price, $email, $now]);
        //         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        //         $delete_cart->execute([$user_id]);
		// 	}
               
        // }
    } 
}

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
		<form action="thanhtoan.php" method="post" >
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
						        		<p><input style= "width:100%; border: 1px solid #ccc; padding: 10px;" type="text" name="name" value="<?= $fetch_profile['name'] ?>"placeholder="Tên học viên"></p>
                                        <span style= "font-size: 15px;"><strong>
                                            Email
                                        </strong></span>

						        		<p><input style= "width:100%; border: 1px solid #ccc; padding: 10px;" type="email" name="email" value="<?= $fetch_profile['email'] ?>" placeholder="Email"></p>
                                        <span style= "font-size: 15px;"><strong>
                                            Địa chỉ
                                        </strong></span>

						        		<p><input style= "width:100%; border: 1px solid #ccc; padding: 10px;" type="text" name="address" value="<?= $fetch_profile['address'] ?>" placeholder="Địa chỉ"></p>
                                        <span style= "font-size: 15px;"><strong>
                                            Số điện thoại
                                        </strong></span>

						        		<p><input style= "width:100%; border: 1px solid #ccc; padding: 10px;" type="tel" name="number" value="<?= $fetch_profile['number'] ?>" placeholder="Số điện thoại"></p>
						        </div>
                                <div style ="padding-left:20px;">
                                    <a href="update_profile.php" class="boxed-btn">Cập nhật thông tin</a>
                                </div>
						      </div>
						    </div>
						  </div>
						  <!-- <div class="card single-accordion">
						    <div class="card-header" id="headingThree">
						      <h5 class="mb-0">
						        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						          Phương thức thanh toán
						        </button>
						      </h5>
						    </div>
						    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
						      <div class="card-body">
						        <div class="card-details">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<label class="input-group-text" for="inputGroupSelect01">Lựa chọn phương thức thanh toán</label>
										</div>

										<select class="custom-select" id="inputGroupSelect01" name="method" required>
											<option value="" disabled selected>Chọn...</option>
											<option value="Momo">Momo</option>
											<option value="Paypal">Paypal</option>
										</select>
									</div>
						        </div>
						      </div>
						    </div>
						  </div> -->
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
                                    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                                    $select_cart->execute([$user_id]);
                                    if($select_cart->rowCount() > 0){
                                        while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                                        $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].') - ';
                                        $total_course = implode($cart_items);
                                        $grand_total += ($fetch_cart['price']);
                                ?>
								<input type="hidden" name="total_course" value="<?= $total_course; ?>">
								<input type="hidden" name="grand_total" value="<?php echo $grand_total; ?>">
								<input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
								<input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
								<input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
								<input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
								<!-- <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>"> -->

								<tr>
									<td style ="font-size: 15px;"><?= $fetch_cart['name']." "; ?></td>
									<td style ="font-size: 15px;"><?= number_format($fetch_cart['price']) . " VNĐ"; ?></td>
                                    
								</tr>
                                
								<?php
                                
                                        }
                                        
                                    }else{
                                        echo '<p style="color: red;" class="empty">Giỏ hàng trống!</p>';
                                    }
                                ?>
                                <tr class="total-data">
									<td><strong>Tổng tiền: </strong></td>
									<td><?php echo number_format($grand_total). " VNĐ"; ?></td>
								</tr>
							</tbody>
						</table>
                        <a href="giohang.php" class="boxed-btn">GIỎ HÀNG</a>			
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
	<?php include 'components/chatbox.php'; ?>

	<!-- footer -->
	<?php include 'components/user_footer.php'; ?>
</body>
</html>