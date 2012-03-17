<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
</head>
<body>
<?php
require_once("config.php");
$system = new system();
echo "<script type='text/javascript'>\n";
echo "console.log($.parseJSON(".json_encode(json_encode($system->getAllEvents()))."))\n";
echo "</script>\n";
?>

</body>
</html>
