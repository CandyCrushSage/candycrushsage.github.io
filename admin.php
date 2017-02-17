<?php
require_once('web-push/WebPush.php');
use Minishlink\WebPush;
$mysqli = new mysqli('localhost', 'root', '', 'notificari');
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

$query = "SELECT COUNT(id) as total FROM notificari ";
$res = $mysqli->query($query)->fetch_array();

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Browser notifications</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
<body>
<div class="container">
  <div id='alerte'></div>
<h3>Trimite notificare catre <strong><?=$res['total']?></strong> browsere</h3>

<form>
<div class="form-group col-xs-6">
   <label for="notificareTitlu">Titlu</label>
   <input type="text" class="form-control" id="notificareTitlu" placeholder="Titlul notificarii">
   <label for="notificareIcon">Iconita</label>
   <input type="text" class="form-control" id="notificareIcon" placeholder="Iconita notificarii" value="http://static.stirileprotv.ro/static/ro/shared/img/bg/logo-stirileprotv.png">
   <label for="notificareMesaj">Mesaj</label>
   <input type="text" class="form-control" id="notificareMesaj" placeholder="Mesajul notificarii (scurt, informativ)" >
   <label for="notificareUrl">Url</label>
   <input type="text" class="form-control" id="notificareUrl" placeholder="Iconita notificarii" >

   <input type="button" class="btn btn-warning" id="notificareTest" value="Testeaza">
   <input type="button" class="btn btn-primary" id="notificareTrimite" value="Trimite">
 </form>
 </div>
</div>


<script>
$( document ).ready(function() {
  $("#notificareTest").click(function(){
    if (Notification.permission !== 'granted') {
      $("#alerte").html("<div class='alert alert-danger'> Nu ai dat permisiuni browserului sa iti afiseze notificari. <a href='#' onclick='Notification.requestPermission()'>Click aici</a> apoi dati refresh la pagina</div>");

    } else if (Notification.permission === "blocked") {
     /* the user has previously denied push. Can't reprompt. */
    } else {
      //show notification
      var e = new Notification($('#notificareTitlu').val(), {
       body: $('#notificareMesaj').val(),
      icon : $('#notificareIcon').val(),
      tag: 'idstireptaevitarepetitia',

      });

      e.onclick = function() {
          document.location = $('#notificareUrl').val();
      };
    }

  });

  $("#cerePermisiune").on('click', function(){

  });

});

</script>
</body>
</html>
