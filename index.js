//THIS IS THE WEB SERVER

const app = require("express")();
const server = require("http").Server(app);
const io = require("socket.io")(server);
const express = require("express");

const dotenv = require("dotenv");
dotenv.config();

//MONGODB
const mongoose = require("mongoose");
const ChatDB = require("./chatDB");
mongoose.connect(process.env.DB, {
  useMongoClient: true
});
//--MONGODB

//serve static files (css, images, etc) inside 'public' folder
app.use(express.static("public"));

//serve files when user types path
app.get("/", (req, res) => {
  res.sendFile(__dirname + "/public/index.html");
});

app.get("/tech", (req, res) => {
  res.sendFile(__dirname + "/public/tech.html");
});

app.get("/friends", (req, res) => {
  res.sendFile(__dirname + "/public/friends.html");
});

//tech namespace
const tech = io.of("/tech");
tech.on("connection", socket => {
  //when someone connects (the client from index.html)
  socket.on("join", data => {
    socket.join(data.room);
    tech
      .in(data.room)
      .emit("login", `${data.user} joined ${data.room} chat room`);
  });
  //socket listens to the client events
  socket.on("message", data => {
    console.log(`${data.user}: ${data.msg}`);
    const room = data.room;
    const user = data.user;
    const msg = data.msg;
    //io (tech, etc) is the server
    tech.in(data.room).emit("message", { room, user, msg });

    //MONGODB
    const chatDb = new ChatDB({
      _id: new mongoose.Types.ObjectId(),
      user: data.user,
      message: data.msg
    });
    chatDb
      .save()
      .then(result => {
        console.log(result);
      })
      .catch(err => console.log(err));
    //--MONGODB
  });

  socket.on("disconnect", () => {
    console.log("user disconnected");
    tech.emit("message", "user disconnected");
  });

  socket.on("user", data => {
    console.log(`user is: ${data.user}`);
  });

  socket.on("loadchat", () => {
    ChatDB.find({}, (err, result) => {
      if (err) {
        console.log(err);
        return;
      }
      result.forEach(ele => {
        tech.in("tech").emit("message", {
          user: ele.user,
          msg: ele.message
        });
      });
    });
  });
});

const PORT = process.env.PORT || 4000;

server.listen(PORT, () => {
  console.log(`server is running on port ${PORT}`);
});
