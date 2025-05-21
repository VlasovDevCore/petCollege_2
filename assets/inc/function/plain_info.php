<?php 

$option = $_GET["option"];

$conn = mysqli_connect("localhost", "root", "", "lkf");
$result = mysqli_query($conn, "SELECT * FROM plain_map WHERE model='$option'");

$html = "";

while ($row = mysqli_fetch_assoc($result)) {
  $html .= $row["script"];
}

echo $html;

?>