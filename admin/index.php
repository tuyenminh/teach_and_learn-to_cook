<script
    src="ckeditor/ckeditor.js">
    if (window.history.replaceState) {
		window.history.replaceState(null, null, window.location.href);
	}
</script>
<?php
ob_start();
include_once("../components/connect.php");
session_start();
if(isset($_SESSION['mail'])&&isset($_SESSION['pass'])){
    $name = $_SESSION['name'];
    $password = $_SESSION['password'];
    $sql = "SELECT * FROM `admin`
    WHERE name = '$name' AND password = '$password'";    
    $query = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($query);
    if ($row == true) {
        // define("admin", true);
        include_once('admin.php');
    } else {
        // define("btv", true);
        include_once('admin.php');
    }
}
else{
    include_once('admin_login.php');
}
?>