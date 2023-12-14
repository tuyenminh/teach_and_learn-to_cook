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
            data.addColumn('number', 'Doanh thu');
            data.addRows(<?php echo json_encode($data_daily_revenue); ?>);

            var options = {
                title: 'Tổng hợp doanh thu theo khoảng thời gian',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('daily_revenue_chart'));
            chart.draw(data, options);
        }
    </script>