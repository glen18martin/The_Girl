<html>
    <head>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js'></script>
    </head>
    <body>
        <script>
            var socket = io('http://13.126.74.47:8000');

            socket.emit('client_connect', { type: 'acp' }, function (data) {
                console.log("Connect ACK");
                socket.emit('list_clients', {}, function (data) {
                    console.log(data);
                });
            });


            

        </script>
    </body>
</head>