<form method="post" style=" display: flex;
  justify-content: center;
  align-items: center;
  margin: 0 auto;">
  <label style="color:#000">
    Chọn năm doanh thu: 
  </label>
        <select name="year" id="year" style="margin: 20px;">
            <?php
            // Kết nối cơ sở dữ liệu

            // Truy vấn danh sách các năm có trong cơ sở dữ liệu
            $query = "SELECT DISTINCT YEAR(regis_date) AS year FROM receipt ORDER BY year DESC";
            $result = $conn->query($query);

            // Lấy năm đã chọn nếu có
            $selectedYear = isset($_POST["year"]) ? $_POST["year"] : "";

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $year = $row['year'];
                $selected = ($year == $selectedYear) ? "selected" : "";
                echo "<option value='{$year}' {$selected}>{$year}</option>";
            }
            ?>
        </select>
        <input class="btn-sm" type="submit" value="Xem biểu đồ">
    </form>

    <!-- Div để hiển thị biểu đồ -->
    <div id="curve_chart" style="width: 900px; height: 500px"></div>