<?php
$mysqli = new mysqli('localhost', 'root', '', 'notificari');
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}
echo "<pre>";
var_dump($_POST);
if(isset($_POST['endpoint'])) {

  $query = "SELECT id FROM notificari WHERE endpoint= '{$_POST['endpoint']}' ";
  $res = $mysqli->query($query)->fetch_array();

  if(!$res) {
    $query = "INSERT INTO notificari VALUES (NULL, '" . $_POST['endpoint'] . "', '" . $_POST['detalii'] . "', '". $_POST['abonat']."')";
  } else {
    $query = "UPDATE notificari SET `detalii`='{$_POST['detalii']}', `abonat`='{$_POST['abonat']}' WHERE id='{$res['id']}'";
  }
echo $query."<br>";
$mysqli->query($query);
}

 ?>
