<?php
include '../components/connect.php';

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d', strtotime('-1 year'));
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');

$query_daily_revenue = "SELECT DATE(receipt_date) AS date, SUM(total) AS daily_revenue
                        FROM receipts
                        WHERE receipt_date BETWEEN :start_date AND :end_date
                        GROUP BY DATE(receipt_date)
                        ORDER BY date ASC";

$result_daily_revenue = $conn->prepare($query_daily_revenue);
$result_daily_revenue->bindParam(':start_date', $start_date);
$result_daily_revenue->bindParam(':end_date', $end_date);
$result_daily_revenue->execute();

$data_daily_revenue = [];
while ($row_daily_revenue = $result_daily_revenue->fetch(PDO::FETCH_ASSOC)) {
    $date = $row_daily_revenue['date'];
    $daily_revenue = (float) $row_daily_revenue['daily_revenue'];
    $data_daily_revenue[] = [$date, $daily_revenue];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biểu đồ tổng hợp doanh thu theo khoảng ngày</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
    <h1>Biểu đồ tổng hợp doanh thu theo khoảng ngày</h1>

    <form method="POST" action="">
        <label for="start_date">Từ ngày:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>">

        <label for="end_date">Đến ngày:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>">

        <button type="submit">Thống kê</button>
    </form>

    <div id="daily_revenue_chart" style="width: 900px; height: 500px;"></div>

    <script type="text/javascript">
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawDailyRevenueChart);

        function drawDailyRevenueChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Ngày');
            data.addColumn('number', 'Doanh thu hàng ngày');
            data.addRows(<?php echo json_encode($data_daily_revenue); ?>);

            var options = {
                title: 'Tổng hợp doanh thu theo khoảng ngày',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('daily_revenue_chart'));
            chart.draw(data, options);
        }
    </script>
</body>
</html>
