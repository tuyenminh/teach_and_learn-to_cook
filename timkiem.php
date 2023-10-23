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
<div class="search-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<span class="close-btn"><i class="fas fa-window-close"></i></span>
					<div class="search-bar">
						<div class="search-bar-tablecell">
							<h3>Tìm kiếm:</h3>
                            <form method="POST" action="tk.php">
                                <input type="text"  name="keyword" placeholder="Từ khóa">
                                <button type="submit">Tìm kiếm <i class="fas fa-search"></i></button>
                            </form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>