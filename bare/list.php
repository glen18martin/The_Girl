<html>
    <head>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js'></script>
    </head>
    <body>
        <script>
            var socket = io('http://13.126.74.47:8000');

            socket.emit('client_connect', { name: 'admin', type: 'acp' }, function (data) {
                console.log("Connect ACK");
                socket.emit('list_clients', null, function (data) {
                    console.log("Listing clients...");
                    console.log(data);


                    socket.emit('send_client_cmd', { toclient: 1, cmd: 'ls' }, function (data) {
                        console.log(data) 
                    });



                    
                });
            });


            

        </script>
    </body>
</head>