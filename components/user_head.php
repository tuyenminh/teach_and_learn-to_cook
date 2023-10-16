<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Responsive Bootstrap4 Shop Template, Created by Imran Hossain from https://imransdesign.com/">

	<!-- title -->
	<title>CookingFood</title>

	<!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/img/favicon.png">
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
	<!-- fontawesome -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/all.min.css">
	<!-- bootstrap -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/bootstrap/css/bootstrap.min.css">
	<!-- owl carousel -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/owl.carousel.css">
	<!-- magnific popup -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/magnific-popup.css">
	<!-- animate css -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/animate.css">
	<!-- mean menu css -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/meanmenu.min.css">
	<!-- main style -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/main.css">
	<!-- responsive -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/responsive.css">
<!-- Jquery CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        //To minimise the height of chatbox
        $(document).ready(function() {
            window.addEventListener('dfMessengerLoaded', function (event) {
            $r1 = document.querySelector("df-messenger");
            $r2 = $r1.shadowRoot.querySelector("df-messenger-chat");
            $r3 = $r2.shadowRoot.querySelector("df-messenger-user-input"); //for other mods
            var sheet = new CSSStyleSheet;
            // manage box height from here
            sheet.replaceSync( `div.chat-wrapper[opened="true"] { height: 350px; width: 300px  }`);
            $r2.shadowRoot.adoptedStyleSheets = [ sheet ];
            });
        });
    </script>
	

</head>