<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="//js.pusher.com/2.2/pusher.min.js" type="text/javascript"></script>
  <script type="text/javascript">
    // Enable pusher logging - don't include this in production
    Pusher.log = function(message) {
      if (window.console && window.console.log) {
        window.console.log(message);
      }
    };

    var pusher = new Pusher('abf9e1b50d1d8e2fc138');
    var channel = pusher.subscribe('mfi-office');
    channel.bind('timelog', function(data) {
      alert(data.message);
    });
  </script>
</head>