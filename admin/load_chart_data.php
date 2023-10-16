<?php

include '../components/connect.php';

if (isset($_POST["default"])) {
    // Truy vấn cơ sở dữ liệu để lấy dữ liệu doanh thu tổng hợp các năm
    $query = "SELECT YEAR(regis_date) AS year, SUM(total_price) AS revenue
              FROM receipt
              GROUP BY YEAR(regis_date)
              ORDER BY year ASC";
    $stmt = $conn->query($query);

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = [$row['year'], (int)$row['revenue']];
    }

    echo json_encode($data);
} elseif (isset($_POST["year"])) {
    // Xử lý yêu cầu của người dùng như trong mã ban đầu
    $selectedYear = $_POST["year"];
    $query = "SELECT MONTH(regis_date) AS month, SUM(total_price) AS revenue
              FROM receipt
              WHERE YEAR(regis_date) = :year
              GROUP BY MONTH(regis_date)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":year", $selectedYear, PDO::PARAM_INT);
    $stmt->execute();

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = [$row['month'], (int)$row['revenue']];
    }

    echo json_encode($data);
}
?>