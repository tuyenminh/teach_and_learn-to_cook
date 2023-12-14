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
    <script type="text/javascript">
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Ngày');
            data.addColumn('number', 'Doanh thu');
            data.addRows(<?php echo json_encode($data_revenue); ?>);

            var options = {
                title: 'Tổng hợp doanh thu theo khoảng ngày',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('revenues_chart'));
            chart.draw(data, options);
        }
    </script>