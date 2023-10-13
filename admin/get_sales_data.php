<?php
// Kết nối cơ sở dữ liệu (thay đổi thông tin kết nối tới cơ sở dữ liệu của bạn)
include '../components/connect.php';

// Lấy năm từ yêu cầu AJAX (sử dụng năm 2022 mặc định)
$selectedYear = '2021';

// Truy vấn cơ sở dữ liệu để lấy dữ liệu doanh thu cho năm được chọn
$query = "SELECT DATE_FORMAT(regis_date, '%Y-%m') AS yearmonth, SUM(total_price) AS total_price
          FROM receipt
          WHERE DATE_FORMAT(regis_date, '%Y') = :selectedYear
          GROUP BY DATE_FORMAT(regis_date, '%Y-%m')
          ORDER BY DATE_FORMAT(regis_date, '%Y-%m')";

$stmt = $conn->prepare($query);
$stmt->bindParam(':selectedYear', $selectedYear, PDO::PARAM_STR);
$stmt->execute();

$data = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = array($row['yearmonth'], (float)$row['total_price']);
}

// Trả về dữ liệu dưới dạng JSON
echo json_encode($data);
?>
