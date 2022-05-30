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
app.get('/', (req, res) => {
  res.send('<h1>Hello world</h1>');
});
io.use((socket, next) => {
  const username = socket.handshake.auth.username;
  if (!username) {
    return next(new Error("invalid username"));
  }
  socket.username = username;
  next();
});
io.on('connection', async (socket) => {
  console.log('a user connected '+ socket.username);
  const users = [];
  for (let [id, socket] of io.of("/").sockets) {
    users.push({
      userID: id,
      username: socket.username,
    });
  }
  // socket.emit("users", users);

  socket.broadcast.emit('users', users);


  socket.on('disconnect', (socket) => {
    console.log('user disconnecte '+ socket.username);
  });
  
  socket.on('chat message', ({ content, to }) => {
    console.log(content,to)
    socket.to(to).emit("chat message", {
      content,
      from: socket.id,
    });
  });
});

server.listen(4000, () => {
  console.log('listening on *:3000');
});
