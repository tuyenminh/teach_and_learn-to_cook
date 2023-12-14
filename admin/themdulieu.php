<?php
include '../components/connect.php';

session_start();

// Xử lý dữ liệu
$data = []; // Mảng chứa dữ liệu cho biểu đồ
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $year = date('Y', strtotime($row['receipt_date']));
    $data[$year][] = (float) $row['total'];
}

// Tạo biểu đồ
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biểu đồ Danh thu theo Năm</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="revenueChart" width="800" height="400"></canvas>
    <script>
        var ctx = document.getElementById('revenueChart').getContext('2d');
        var years = <?php echo json_encode(array_keys($data)); ?>;
        var datasets = <?php echo json_encode(array_values($data)); ?>;

        var chartData = {
            labels: years,
            datasets: datasets.map((dataset, index) => ({
                label: 'Năm ' + years[index],
                data: dataset,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }))
        };

        var config = {
            type: 'bar',
            data: chartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        var myChart = new Chart(ctx, config);
    </script>
</body>
</html>
