<?php
// Kết nối cơ sở dữ liệu (thay đổi thông tin kết nối tới cơ sở dữ liệu của bạn)
include '../components/connect.php';

// Truy vấn cơ sở dữ liệu để lấy danh sách các năm
$query = "SELECT DISTINCT YEAR(regis_date) AS year FROM receipt ORDER BY year DESC";

$stmt = $conn->query($query);
$years = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Trả về danh sách các năm dưới dạng JSON
echo json_encode($years);
?>
