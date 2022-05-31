const express = require('express');
const app = express();
const http = require('http');
const server = http.createServer(app);
const { Server } = require("socket.io");
const io = new Server(server, {

    cors: {
        origins: "*:*",
        method: ["GET", "POST", "PUT", "PATCH"],
    }
});

const crypto = require("crypto");
const randomId = () => crypto.randomBytes(8).toString("hex");

const { InMemorySessionStore } = require("./sessionStore");
const sessionStore = new InMemorySessionStore();
app.get('/', (req, res) => {
    res.send('<h1>Hello world</h1>');
});
io.use((socket, next) => {
    const sessionID = socket.handshake.auth.sessionID;
    if (sessionID) {
        // find existing session
        const session = sessionStore.findSession(sessionID);
        if (session) {
            socket.sessionID = sessionID;
            socket.userID = session.userID;
            socket.username = session.username;
            return next();
        }
    }
    const username = socket.handshake.auth.username;
    if (!username) {
        return next(new Error("invalid username"));
    }
    socket.sessionID = randomId();
    socket.userID = randomId();
    socket.username = username;
    next();
    next();
});
io.on('connection', async (socket) => {


    sessionStore.saveSession(socket.sessionID, {
        userID: socket.userID,
        username: socket.username,
        connected: true,
    });

    socket.emit("session", {
        sessionID: socket.sessionID,
        userID: socket.userID,
        // username: socket.username,
    })

          console.log('a user connected ' + socket.username);
        //   const users = [];
        //   for (let [id, socket] of io.of("/").sockets) {
        //     users.push({
        //       userID: id,
        //       username: socket.username,
        //     });
        //   }
        // socket.emit("users", users);

        // socket.broadcast.emit("user connected", {
        //   userID: socket.id,
        //   username: socket.username,
        // });

        ;

    socket.join(socket.userID);

    const users = [];
    sessionStore.findAllSessions().forEach((session) => {
        users.push({
            userID: session.userID,
            username: session.username,
            connected: session.connected,
        });
    });
    socket.emit("users", users);
    socket.broadcast.emit("user connected", {
        userID: socket.userID,
        username: socket.username,
        connected: true,
      });
    
    socket.on("disconnect", async () => {
        const matchingSockets = await io.in(socket.userID).allSockets();
        const isDisconnected = matchingSockets.size === 0;
        if (isDisconnected) {
            // notify other users
            console.log('a user disconnect ' + socket.username);

            socket.broadcast.emit("user disconnected", socket.userID);
            // update the connection status of the session
            sessionStore.saveSession(socket.sessionID, {
                userID: socket.userID,
                username: socket.username,
                connected: false,
            });
        }
    });

    socket.on('chat message', ({ content, to }) => {
        console.log(content, to)
        socket.to(to).to(socket.userID).emit("chat message", {
            content,
            from: socket.id,
            to,
        });
    });
});

server.listen(4000, () => {
    console.log('listening on *:3000');
});
