<?php

include 'components/connect.php';
require 'vendor/autoload.php';

class SearchElastic
{
    private $elasticclient = null;

    public function __construct()
    {
        $hosts = ['http://localhost:9200']; // Điền URL của Elasticsearch container

        $this->elasticclient = Elastic\Elasticsearch\ClientBuilder::create()
            ->setHosts($hosts)
            ->build();

//     Thử truy vấn Elasticsearch để kiểm tra kết nối
            // try {
            //     $response = $client->ping();
            //     if ($response) {
            //         echo 'Kết nối thành công đến Elasticsearch!';
            //     } else {
            //         echo 'Kết nối không thành công đến Elasticsearch.';
            //     }
            // } catch (Exception $e) {
            //     echo 'Lỗi: ' . $e->getMessage();
            // }
    }
    public function Mapping(){
        $params = [
            'index' => 'courses',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'name' => [
                            'type' => 'text',
                            'analyzer' => 'vietnamese_analyzer',
                        ],
                        'description' => [
                            'type' => 'text',
                            'analyzer' => 'vietnamese_analyzer',
                        ],
                        // Các trường khác mà bạn không muốn áp dụng analyzer tiếng Việt
                        'id' => [
                            'type' => 'integer',
                        ],
                        'category' => [
                            'type' => 'keyword',
                        ],
                        'price' => [
                            'type' => 'float',
                        ],
                        'image' => [
                            'type' => 'text',
                        ],
                        'opening_day' => [
                            'type' => 'date',
                        ],
                        'id_cate' => [
                            'type' => 'integer',
                        ],
                    ],
                ],
            ],
        ];
    $this->elasticclient->indices()->create($params);   
    }

    public function InsertData()
    {
        
        $client = $this->elasticclient;
        $sql = "SELECT courses.id, courses.name, courses.description, courses.image, courses.opening_day, courses.id_cate
                FROM courses
                LEFT JOIN category ON courses.id_cate = category.id_cate";
        
        // Thực hiện truy vấn SQL bằng PDO
        $result = $conn->query($sql);
        
        $params = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'courses', // Tên index Elasticsearch
                    '_type' => '_doc', // Loại tài liệu, thường được đặt là '_doc' trong Elasticsearch 7.x trở lên
                    '_id' => $row['id'], // Sử dụng một trường có giá trị định danh duy nhất làm ID
                ],
            ];

            $params['body'][] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'image' => $row['image'],
                'opening_day' => $row['opening_day'],
                'category' => $row['category'],
                'id_cate' => $row['id_cate'],
            ];
        }

        // Áp dụng bộ phân tích tiếng Việt cho các trường cần thiết
        $this->ApplyVietnameseAnalyzer();

        $responses = $client->bulk($params);
        return true;
    }

    public function ApplyVietnameseAnalyzer()
    {
        $client = $this->elasticclient;

        $params = [
            'index' => 'courses', // Tên index Elasticsearch
            'body' => [
                'settings' => [
                    'analysis' => [
                        'analyzer' => [
                            'vietnamese_analyzer' => [
                                'type' => 'custom',
                                'tokenizer' => 'vi_tokenizer',
                            ],
                        ],
                    ],
                ],
                'mappings' => [
                    'properties' => [
                        'name' => [
                            'type' => 'text',
                            'analyzer' => 'vietnamese_analyzer',
                        ],
                        'description' => [
                            'type' => 'text',
                            'analyzer' => 'vietnamese_analyzer',
                        ],
                        // Áp dụng analyzer cho các trường khác nếu cần
                    ],
                ],
            ],
        ];

        $client->indices()->create($params);
    }
    public function Search($query)
    {
        $client = $this->elasticclient;
        $result = array();
        $i = 0;
        $params = [
            'index' => 'courses',
            'type' => '_doc',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['name', 'description'], // Trường bạn muốn tìm kiếm
                        'analyzer' => 'vietnamese_analyzer',
                    ],
                ],
            ],
        ];

        $query = $client->search($params);
        $hits = sizeof($query['hits']['hits']);
        $hit = $query['hits']['hits'];
        $result['searchfound'] = $hits;
        while ($i < $hits) {
            $result['result'][$i] = $query['hits']['hits'][$i]['_source'];
            $i++;
        }
        return $result;
    }
    
}
// Gọi hàm Search và lấy kết quả
$searchElastic = new SearchElastic();
$query = $_POST['keyword'];
$searchResult = $searchElastic->Search($query);

if ($searchResult['searchfound'] > 0) {
    // Hiển thị kết quả tìm kiếm
    // Ví dụ: dùng vòng lặp để hiển thị danh sách kết quả
    foreach ($searchResult['result'] as $result) {
        echo "<p>{$result['name']}</p>";
    }
} else {
    // Hiển thị thông báo lỗi nếu không tìm thấy kết quả
    echo "Không tìm thấy kết quả nào.";
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Tìm Kiếm</title>
</head>
<body>
    <h1>Tìm Kiếm</h1>
    <form method="POST" action="timkiem.php">
        <input type="text" name="keyword" placeholder="Nhập từ khóa tìm kiếm">
        <button type="submit">Tìm Kiếm</button>
    </form>
    <div id="search-results">
    <!-- Kết quả tìm kiếm sẽ được hiển thị ở đây -->
</div>
<script>
    // Gắn sự kiện khi người dùng bấm nút tìm kiếm
    document.querySelector('button[type="submit"]').addEventListener('click', function(event) {
        event.preventDefault(); // Ngăn chặn nút submit gửi biểu mẫu

        // Lấy giá trị từ trường nhập
        var keyword = document.querySelector('input[name="keyword"]').value;

        // Gửi truy vấn tìm kiếm đến máy chủ bằng AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'timkiem.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Xử lý kết quả tìm kiếm và hiển thị nó lên giao diện
                var searchResults = document.getElementById('search-results');
                searchResults.innerHTML = xhr.responseText;
            }
        };
        xhr.send('keyword=' + keyword);
    });
</script>
<!-- <script>
    xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
        if (xhr.status === 200) {
            // Xử lý kết quả tìm kiếm và hiển thị nó lên giao diện
            var searchResults = document.getElementById('search-results');
            searchResults.innerHTML = xhr.responseText;
        } else {
            // Xử lý lỗi và hiển thị thông báo lỗi
            var searchResults = document.getElementById('search-results');
            searchResults.innerHTML = "Đã xảy ra lỗi trong quá trình tìm kiếm.";
        }
    }
};

</script> -->

</body>
</html>
