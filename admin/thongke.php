<?php
$connect = new mysqli('localhost', 'root', '', 'food');
$query = "SELECT `category`.*, COUNT(courses.id_cate) AS 'number_courses' FROM courses INNER JOIN `category` ON courses.id_cate = category.id GROUP BY courses.id_cate";
$result = mysqli_query($connect, $query);
$data = [];
while ($row = mysqli_fetch_array($result)) {
    $data[] = $row; // Thêm dòng này để thêm dữ liệu vào mảng $data
}
?>

<html>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            <?php
            if (!empty($data)) { // Kiểm tra nếu có dữ liệu
            ?>
            var data = google.visualization.arrayToDataTable([
                ['name_cate', 'number_courses'],
                <?php
                foreach ($data as $key) {
                    echo "['" . $key['name_cate'] . "' , " . $key['number_courses'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Thống kê ',
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
            <?php
            } else {
                echo "console.log('Không có dữ liệu để vẽ biểu đồ.');";
            }
            ?>
        }
    </script>
</head>
<body>
<div id="piechart_3d" style="width: 900px; height: 500px;"></div>
</body>
</html>
