<?php
include '../components/connect.php'; // Kết nối cơ sở dữ liệu

// Truy vấn cơ sở dữ liệu để lấy dữ liệu tổng hợp doanh thu qua các năm
$query_revenue = "SELECT YEAR(receipt_date) AS year, SUM(total) AS revenue
          FROM receipts
          GROUP BY YEAR(receipt_date)
          ORDER BY year ASC";
$result_revenue = $conn->query($query_revenue);

// Tạo một mảng chứa dữ liệu doanh thu
$data_revenue= [];
while ($row_revenue = $result_revenue->fetch(PDO::FETCH_ASSOC)) {
    $year = $row_revenue['year'];
    $revenue = (float) $row_revenue['revenue'];
    $data_revenue[] = [$year, $revenue];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biểu đồ tổng hợp doanh thu</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
    <h1>Biểu đồ tổng hợp doanh thu qua các năm</h1>

    <div id="revenue_chart" style="width: 900px; height: 500px;"></div>

    <script type="text/javascript">
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Năm');
            data.addColumn('number', 'Doanh thu');
            data.addRows(<?php echo json_encode($data_revenue); ?>);

            var options = {
                title: 'Tổng hợp doanh thu qua các năm',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('revenue_chart'));
            chart.draw(data, options);
        }
    </script>
</body>
</html>
