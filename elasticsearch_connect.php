<?php
require 'vendor/autoload.php';

class SearchElastic
{
    private $elasticclient = null;
    public function __construct()
{
    $hosts = ['http://localhost:9200']; // Điền URL của Elasticsearch container

    $this->elasticclient = Elastic\Elasticsearch\ClientBuilder::create()
        ->setHosts($hosts)
        ->setBasicAuthentication('elastic', 'w8FpHGRzbm-etOer58GU') // Thay 'your_username' và 'your_password' bằng tên người dùng và mật khẩu xác thực của bạn
        ->build();
}

   

    public function checkElasticsearchConnection()
    {
        try {
            $response = $this->elasticclient->ping();
            if ($response) {
                echo 'Kết nối thành công đến Elasticsearch!';
            } else {
                echo 'Kết nối không thành công đến Elasticsearch.';
            }
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    }
    public function Mapping(){
        $params = [
            'index' => 'courses',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'name' => [
                            'type' => 'text',
                            'analyzer' => 'vi_analyzer',
                        ],
                        'description' => [
                            'type' => 'text',
                            'analyzer' => 'vi_analyzer',
                        ],

                        'id' => [
                            'type' => 'integer',
                        ],
                        'price' => [
                            'type' => 'integer',
                        ],
                        'image' => [
                            'type' => 'text',
                        ],
                        'opening_day' => [
                            'type' => 'date',
                        ],
                        'study_time' => [
                            'type' => 'text',
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

    public function CheckMapping()
    {
        $indexName = 'courses';

        // Lấy thông tin về mapping của index
        $mapping = $this->elasticclient->indices()->getMapping(['index' => $indexName]);

        // Kiểm tra mapping của các trường
        $properties = $mapping[$indexName]['mappings']['properties'];

        // Kiểm tra mapping của trường 'name'
        if (isset($properties['name']['type']) && $properties['name']['type'] === 'text' && isset($properties['name']['analyzer']) && $properties['name']['analyzer'] === 'vi_analyzer') {
            echo 'Mapping cho trường "name" đã được áp dụng thành công.';
        } else {
            echo 'Lỗi: Mapping cho trường "name" không được áp dụng đúng cách.';
        }

        // Kiểm tra mapping của trường 'description'
        if (isset($properties['description']['type']) && $properties['description']['type'] === 'text' && isset($properties['description']['analyzer']) && $properties['description']['analyzer'] === 'vi_analyzer') {
            echo 'Mapping cho trường "description" đã được áp dụng thành công.';
        } else {
            echo 'Lỗi: Mapping cho trường "description" không được áp dụng đúng cách.';
        }
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
                'study_time' => $row['study_time'],
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
                            'analyzer' => 'vi_analyzer',
                        ],
                        'description' => [
                            'type' => 'text',
                            'analyzer' => 'vi_analyzer',
                        ],
                        // Áp dụng analyzer cho các trường khác nếu cần
                    ],
                ],
            ],
        ];

        $client->indices()->create($params);
    }

    

    
}

// Sử dụng class và kiểm tra kết nối
$searchElastic = new SearchElastic();
$searchElastic->checkElasticsearchConnection();
$searchElastic->CheckMapping();

?>
