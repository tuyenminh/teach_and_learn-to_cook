  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div>
  </footer>
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="../plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="../plugins/raphael/raphael.min.js"></script>
<script src="../plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="../plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard2.js"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['name_cate', 'number_courses'],

         <?php
		 	foreach ($data as $key){
			echo "['".$key['name_cate']."', ".$key['number_courses']."],";
			} 
		 ?>
        ]);

      var options = {
        colors: ['#608da2', '#1dadc0', '#779eb2', '#446879'],
        legend: 'none',
        pieSliceText: 'label',
        // title: 'Thống kê khóa học',
        pieStartAngle: 100,
		pieHole: 0.4,
		chartArea: {
			left: 10, // Điều chỉnh khoảng cách từ lề trái
			top: 10, // Điều chỉnh khoảng cách từ lề trên
			right: 10, // Điều chỉnh khoảng cách từ lề phải
			bottom: 10, // Điều chỉnh khoảng cách từ lề dưới
		},

        
      };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['name_cate', 'number_courses'],

         <?php
		 	foreach ($data1 as $key){
			echo "['".$key['name_cate']."', ".$key['number_recipes']."],";
			} 
		 ?>
        ]);

      var options = {
        colors: ['#608da2', '#1dadc0', '#779eb2', '#446879'],
        legend: 'none',
        pieSliceText: 'label',
        title: 'Thống kê công thức',
        pieStartAngle: 100,
      };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_recipe'));
    
        chart.draw(data, options);
      }
    </script>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Khởi tạo biến dataPHP
            var dataPHP = [];

            <?php
            if(isset($_POST["year"])) {
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

                // Chuyển dữ liệu sang định dạng JSON
                echo "dataPHP = " . json_encode($data) . ";";
            }
            ?>

            // Tạo dữ liệu cho biểu đồ
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Tháng');
            data.addColumn('number', 'Doanh thu');
            data.addRows(dataPHP);

            // Tùy chọn của biểu đồ
            var options = {
                title: 'Doanh thu hàng tháng',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            // Vẽ biểu đồ
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
        }
    </script>
  

  
  <script type="text/javascript">
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Năm');
            data.addColumn('number', 'Doanh thu');
            data.addRows(<?php echo json_encode($data); ?>);

            var options = {
                title: 'Tổng hợp doanh thu qua các năm',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('revenue_chart'));
            chart.draw(data, options);
        }
    </script>

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
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('revenue_chart'));
            chart.draw(data, options);
        }
    </script>