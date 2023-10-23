<!DOCTYPE html>
<html>
<head>
    <title>Tìm Kiếm</title>
</head>
<body>
    <h1>Tìm Kiếm</h1>
    <form method="POST" action="tk.php">
        <input type="text" name="keyword" placeholder="Nhập từ khóa tìm kiếm">
        <button type="submit">Tìm Kiếm</button>
    </form>

    <?php
    include 'elasticsearch_connect.php';

    // Tạo một đối tượng của lớp SearchElastic
    $searchElastic = new SearchElastic();

    // Xử lý tìm kiếm và hiển thị kết quả
    if (isset($_POST['keyword'])) {
        $keyword = $_POST['keyword'];
        $searchResult = $searchElastic->Search($keyword);

        if ($searchResult['searchfound'] > 0) {
            echo "<h2>Kết quả tìm kiếm cho '$keyword':</h2>";
            echo "<ul>";
            foreach ($searchResult['result'] as $item) {
                echo "<li>";
                echo "Tên: " . $item['name'] . "<br>";
                // Hiển thị thông tin khác nếu cần
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "Không tìm thấy kết quả cho '$keyword'.";
        }
    }
    ?>
</body>
</html>
