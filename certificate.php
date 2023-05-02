
<?php
include 'components/connect.php';
?>

<html>
    <head>
        <style type='text/css'>
            body, html {
                margin: 0;
                padding: 0;
            }
            body {
                color: black;
                display: table;
                font-family: Georgia, serif;
                font-size: 24px;
                text-align: center;
            }
            .container {
                border: 20px solid tan;
                width: 750px;
                height: 563px;
                display: table-cell;
                vertical-align: middle;
            }
            .logo {
                color: tan;
            }

            .marquee {
                color: tan;
                font-size: 48px;
                margin: 20px;
            }
            .assignment {
                margin: 20px;
            }
            .person {
                border-bottom: 2px solid black;
                font-size: 32px;
                font-style: italic;
                margin: 20px auto;
                width: 400px;
            }
            .reason {
                margin: 20px;
            }
        </style>
    </head>
    <body>
    <?php
        $sql = "SELECT * FROM certificate";
        // $result = mysqli_query($conn, $sql);
      ?>
        <div class="container">
            <div class="logo">
                Khóa học CookingFood
            </div>

            <div class="marquee">
                Chứng chỉ khóa học
            </div>

            <div class="assignment">
                Tên khách hàng
            </div>

            <div class="person">
                <!-- <?php echo $select_cart['name']; 
                ?> -->
                Minh Tuyen
            </div>

            <div class="reason">
                Đã hoàn thành khóa học <br/>
                Chân gà nướng muối ớt
            </div>
        </div>
        
    </body>
</html>