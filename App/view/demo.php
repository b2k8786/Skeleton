<?php
session_start();


$_SESSION['uID'] = strtotime('now');

echo $_SESSION['uID'];
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>EventSource example</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <script>
    function getData() {
      if (typeof window.EventSource == "function") {
        var source = new EventSource('events.php');
        source.addEventListener('open', function(e) {
          // Connection was opened.
        }, false);

        source.addEventListener('message', function(e) {
          jQuery('body').html(e.data);
        }, false);

        source.addEventListener('error', function(e) {
          if (e.readyState == EventSource.CLOSED) {
            // Connection was closed.
          }
        }, false);

        window.onbeforeunload = (e) => {
          source.close();
          e.returnValue = 'Sure?';
        }
      }
    }

    getData();
  </script>

</head>

<body>



</body>

</html>