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
            ->setBasicAuthentication('elastic', 'w8FpHGRzbm-etOer58GU') // Thay 'your_username' và 'your_password' bằng tên người dùng và mật khẩu xác thực của bạn
            ->build();
    }

    public function Mapping()
    {
        $indexName = 'courses';

        // Kiểm tra xem index đã tồn tại hay chưa
        $indexExists = $this->elasticclient->indices()->exists(['index' => $indexName]);

        if (!$indexExists) {
            $params = [
                'index' => $indexName,
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
                            'id' => ['type' => 'integer'],
                            'price' => ['type' => 'integer'],
                            'image' => ['type' => 'text'],
                            'opening_day' => ['type' => 'date'],
                            'study_time' => ['type' => 'text'],
                            'id_cate' => ['type' => 'integer'],
                        ],
                    ],
                ],
            ];
            $this->elasticclient->indices()->create($params);
        }
    }

    public function InsertData($conn)
{
    $indexName = 'courses';

    // Kiểm tra xem index đã tồn tại hay chưa
    $indexExists = $this->elasticclient->indices()->exists(['index' => $indexName]);

    if ($indexExists) {
        // Thực hiện hàm InsertData() chỉ nếu index đã tồn tại.
        $client = $this->elasticclient;
        $sql = "SELECT courses.id, courses.name,  courses.price,  courses.image, courses.description, courses.opening_day, courses.study_time, courses.id_cate FROM courses INNER JOIN category ON courses.id_cate = category.id_cate";
        $result = $conn->query($sql);
        $params = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $params[] = [
                'index' => [
                    '_index' => $indexName, // Sử dụng tên index đã xác định
                ],
            ];

            $params[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'image' => $row['image'],
                'description' => $row['description'],
                'opening_day' => $row['opening_day'],
                'study_time' => $row['study_time'],
                'id_cate' => $row['id_cate'],
            ];
        }

        // Áp dụng bộ phân tích tiếng Việt cho các trường cần thiết
        $this->ApplyVietnameseAnalyzer();

        $responses = $client->bulk(['body' => $params]);
        return true;
    } else {
        echo "Index 'courses' chưa tồn tại. Vui lòng chạy hàm Mapping() trước.";
    }
}

    public function ApplyVietnameseAnalyzer()
    {
        $indexName = 'courses';

        $params = [
            'index' => $indexName,
            'body' => [
                'settings' => [
                    'analysis' => [
                        'analyzer' => [
                            'vi_analyzer' => [
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

        $this->elasticclient->indices()->create($params);
    }
public function Search($keyword)
{
    $client = $this->elasticclient;
    $result = array();
    $i = 0;
    $params = [
        'index' => 'courses', // Thay 'courses' bằng tên index của bạn
        'body' => [
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'match_phrase' => [
                                'name' => [
                                    'query' => $keyword,
                                    'slop' => 50, // Điều này cho phép một số khoảng trắng hoặc từ khóa phụ thuộc cho sự linh hoạt
                                ],
                            ],
                        ],
                        [
                            'match' => [
                                'name' => [
                                    'query' => $keyword,
                                    'analyzer' => 'vi_analyzer',
                                    'fuzziness' => 'AUTO',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];
    
    

    $query = $client->search($params);
    $hits = sizeof($query['hits']['hits']);
    $result['searchfound'] = $hits;

    while ($i < $hits) {
        $result['result'][$i] = $query['hits']['hits'][$i]['_source'];
        $i++;
    }

    return $result;
}



}

// Sử dụng class và kiểm tra kết nối
$searchElastic = new SearchElastic();
// $searchElastic->Mapping(); // Kiểm tra và tạo index nếu cần
// $searchElastic->InsertData($conn); // Thực hiện InsertData() chỉ nếu index đã tồn tại
// $searchElastic->Search($query);




?>
