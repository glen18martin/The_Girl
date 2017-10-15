<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
  <script src='https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js'></script>.


  <script>
            var socket = io('http://13.126.74.47:8000');

            socket.emit('client_connect', { name: 'admin', type: 'acp' }, function (data) {
                console.log("Connect ACK");
                socket.emit('list_clients', null, function (data) {
                    console.log("Listing clients...");
                    console.log(data);
                    document.querySelector("#clients").innerHTML = JSON.stringify(data);


                    socket.emit('send_client_cmd', { toclient: 1, cmd: 'ls' }, function (data) {
                        console.log(data) 
                    });



                    
                });
            });


            

     </script>


</head>
<body>

<div class="jumbotron text-center">
  <h1>My First Bootstrap Page</h1>
  <p>Resize this responsive page to see the effect!</p> 
</div>
  
<div class="container">
  <div class="row">
    <div class="col-sm-12" id="clients">

    </div>

  </div>
</div>

</body>
</html>


