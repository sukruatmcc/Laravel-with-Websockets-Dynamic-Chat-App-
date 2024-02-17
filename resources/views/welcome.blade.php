<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="{{ asset('js/app.js') }}"></script>
    <title>Example</title>
</head>

<body>

    <p>
        <b>Trade:- </b> <span id="trade-data"></span>
</p>

    <script>
          Echo.join('track-message-channel')
          .here((users) => {
            console.log(users.length)
          })
          .joining((user) => {
            console.log('New user joined:- '+user.name)
          })
          .leaving((user) => {
            console.log('user leaved:- '+user.name)
          })
          .listen('.custom-name',(e) => { //eğer channel private ise echo.private kullan. public ise echo.channel kullan. presence ise echo.join kullan
            document.getElementById('trade-data').innerHTML = e.custom_message + ' ' + e.id;
             console.log(e)
         });
    </script>

</body>

</html>
