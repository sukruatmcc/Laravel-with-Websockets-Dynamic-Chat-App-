<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Static Chat App</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>

    <div id="start-chat">
        <form id="save-name">
            <input type="text" name="name" id="name"     placeholder="Enter Name" required>
            <input type="submit" value="Let's Chat">
        </form>
    </div>

    <div id="chat-part" class="mt-3">
        <form id="chat-form">
            @csrf
            <input type="hidden" name="username" id="username">
            <input type="text" name="message" id="message" placeholder="Enter Message" required>
            <input type="submit" value="Send">
        </form>
        <div id="chat-container">

        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        $('#chat-part').hide();

        $('#start-chat').submit(function(event){
            event.preventDefault();
            $('#username').val($('#name').val());
            $('#start-chat').hide();
            $('#chat-part').show();
        })

        $('#chat-form').submit(function(event){
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url:"{{ route('broadcastMessage') }}",
                type:'POST',
                data:formData
            });
            $('#message').val('');
        });

        Echo.channel('message')
        .listen('MessageEvent',(e) => {
            let html = `<br>
            <b>`+e.userName+`:- </b>
            <span>`+e.message+`</span>
            `;
            $('#chat-container').append(html);
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>
