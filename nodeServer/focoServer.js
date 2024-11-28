var fs = require('fs');
var ini = require('ini');

//var configIni = ini.parse(fs.readFileSync('../class/db/conf.ini', 'utf-8')); //RUTA WINDOWS
var configIni = ini.parse(fs.readFileSync('/var/www/html/class/db/conf.ini', 'utf-8')); //RUTA LINUX

configIni = configIni.focoDB;

//var sql = require("mssql");
var mysql = require('mysql');
// config for your database

var Host = configIni.serverDB;
var Port = "";

if(configIni.serverDB.indexOf(':') != -1){
    Host = configIni.serverDB.substring(0, configIni.serverDB.indexOf(':'));
    Port = configIni.serverDB.substring(configIni.serverDB.indexOf(':') + 1,9999);
}else{
    Port = "3306";
}

var db = mysql.createConnection({
    host: Host,
    port: Port,
    user: configIni.userDB,
    password: configIni.passDB,
    database: configIni.DB
});

var ArraySocketsUsuarios = new Object();
var ArrayUsuariosSockets = new Object();

db.connect(function (err) {
    if (err) {
        console.log("Error con credenciales de Base de datos.",err);
    } else {
        var app = require('express')();
        var http = require('http').Server(app);
        var io = require('socket.io')(http);
        var port = process.env.PORT || configIni.portNode;
//	var port = 40005;

        console.log(port);

        http.listen(port);

        require('events').EventEmitter.defaultMaxListeners = Infinity;
        
        io.on('connection', function (socket) {
            var idSocket = socket.id;

            socket.on("createLogin", function (Data) {
                var idUsuario = Data.idUsuario;
                var idCedente = Data.idCedente;
                var idMandante = Data.idMandante;
                db.query("INSERT INTO Usuarios_Activos (idUsuario,idSocket,Id_Cedente,Id_Mandante) values ('" + idUsuario + "','" + idSocket + "','" + idCedente + "','" + idMandante + "')", function (err, recordset) {
                    if (!err) {
                        recordset = JSON.parse(JSON.stringify(recordset));
                        var idLogin = recordset.insertId;
                        io.to(idSocket).emit("loginResponse", { insertID: idLogin });
                        ArraySocketsUsuarios[idLogin] = idSocket;
                        ArrayUsuariosSockets[idSocket] = idLogin;
                        console.log(ArraySocketsUsuarios);
                        console.log(ArrayUsuariosSockets);
                    }
                });
            });
            socket.on("showCalidadNotifications", function (Data) {
                var idGrabacion = Data.idGrabacion;
                var idMandante = Data.idMandante;
                db.query("SELECT Id_Usuario as idUsuario from evaluaciones inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente where evaluaciones.Id_Grabacion='"+idGrabacion+"' and mandante_cedente.Id_Mandante='"+idMandante+"'",function(err,Usuarios) {
                    if(!err){
                        Usuarios = JSON.parse(JSON.stringify(Usuarios));
                        //console.log(Usuarios);
                        for (var i = 0; i < Usuarios.length; i++) {
                            var Row = Usuarios[i];
                            var idUsuario = Row.idUsuario;
                            db.query("SELECT * from Usuarios_Activos where idUsuario='"+idUsuario+"' and Id_Mandante='"+idMandante+"'", function (err, Logins) {
                                if (!err) {
                                    Logins = JSON.parse(JSON.stringify(Logins));
                                    console.log(Logins);
                                    for (var j = 0; j < Logins.length; j++) {
                                        var Row = Logins[j];
                                        var idLogin = Row.id;
                                        var idSocket = ArraySocketsUsuarios[idLogin];
                                        io.to(idSocket).emit("calidadNotifications", { Data: Data });
                                    }
                                }
                            });
                        }
                        
                    }
                });
            });
            socket.on('disconnect', function (Data) {
                var idLogin = ArrayUsuariosSockets[idSocket];
                db.query("DELETE FROM Usuarios_Activos where id='"+idLogin+"'", function (err, recordset) {
                    if (!err) {
                        recordset = JSON.parse(JSON.stringify(recordset));
                        delete ArraySocketsUsuarios[idLogin];
                        delete ArrayUsuariosSockets[idSocket];
                    }
                });
            });
        });
    }
});
