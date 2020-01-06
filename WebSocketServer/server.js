/*jshint esversion: 6 */

var app = require("http").createServer();

// Se tiverem problemas com "same-origin policy" deverão activar o CORS.

// Aqui, temos um exemplo de código que ativa o CORS (alterar o url base)

// var app = require('http').createServer(function(req,res){
// Set CORS headers
//  res.setHeader('Access-Control-Allow-Origin', 'http://---your-base-url---');
//  res.setHeader('Access-Control-Request-Method', '*');
//  res.setHeader('Access-Control-Allow-Methods', 'UPGRADE, OPTIONS, GET');
//  res.setHeader('Access-Control-Allow-Credentials', true);
//  res.setHeader('Access-Control-Allow-Headers', req.header.origin);
//  if ( req.method === 'OPTIONS' || req.method === 'UPGRADE' ) {
//      res.writeHead(200);
//      res.end();
//      return;
//  }
// });

// NOTA: A solução correta depende da configuração do próprio servidor,
// e alguns casos do próprio browser.
// Assim sendo, não se garante que a solução anterior funcione.
// Caso não funcione é necessário procurar/investigar soluções alternativas

var io = require("socket.io")(app);

var LoggedUsers = require("./loggedusers.js");

var GameList = require("./gamelist.js");

app.listen(8080, function() {
  console.log("listening on *:8080");
});

// ------------------------
// Estrutura dados - server
// ------------------------

// loggedUsers = the list (map) of logged users.
// Each list element has the information about the user and the socket id
// Check loggedusers.js file

let loggedUsers = new LoggedUsers();
//let games = new GameList();

io.on("connection", function(socket) {
  console.log("client has connected (socket ID = " + socket.id + ")");

  socket.on('movement_created', movement => {
    console.log('movement created');
    io.sockets.emit('movement_created', movement);

  });

  /*
  socket.on("msg_from_client", function(msg, userInfo) {
    // emits to all clients
    io.sockets.emit("msg_from_server", { msg: msg, user: userInfo });
  });

  socket.on("msg_from_client_to_dep", function(msg, userInfo) {
    // emits to all clients in the channel department_id
    if (userInfo) {
      let channelName = "department_" + userInfo.department_id;
      io.sockets
        .to(channelName)
        .emit("msg_from_server", { msg: msg, user: userInfo });
    }
  });

  socket.on("user_enter", function(user) {
    if (user) {
      socket.join("department_" + user.department_id);
      loggedUsers.addUserInfo(user, socket.id);
    }
  });
  socket.on("user_exit", function(user) {
    if (user) {
      socket.leave("department_" + user.department_id);
      loggedUsers.addUserInfo(user, socket.id);
    }
  });

  socket.on("privateMessage", function(msg, sourceUser, destUser) {
    let userInfo = loggedUsers.userInfoByID(destUser.id);
    let socket_id = userInfo !== undefined ? userInfo.socketID : null;
    if (socket_id === null) {
      socket.emit("privateMessage_unavailable", destUser);
    } else {
      io.to(socket_id).emit("privateMessage", msg, sourceUser);
      socket.emit("privateMessage_sent", msg, destUser);
    }
  });

  socket.on("user_changed", function(changedUser) {
    socket.broadcast.emit("user_changed", changedUser);
  });

  // --------------------------------------------------------------
  // CODE  RELATED TO THE TIC-TAC-TOE GAME
  // COULD HAVE THEIR OWN NAMEPSACE - TRY TO DISCOVER HOW!
  // --------------------------------------------------------------

  // Function to remove all sockets from a room
  deleteRoom = roomName => {
    io.in(roomName).clients(function(error, clients) {
      if (clients.length > 0) {
        clients.forEach(function(socket_id) {
          io.sockets.sockets[socket_id].leave(roomName);
        });
      }
    });
  };

  // Function to remove one game from the list of games
  deleteGame = gameID => {
    let deletedGameClone = games.removeGame(gameID);
    if (deletedGameClone) {
      io.to(deletedGameClone.gameID).emit("game_deleted", deletedGameClone);
      deleteRoom(deletedGameClone.gameID);
      return deletedGameClone;
    }
    return null;
  };

  deleteAllMyGames = () => {
    let userInfo = loggedUsers.userInfoBySocketID(socket.id);
    if (userInfo) {
      let myGames = games.allGameIDsOfUser(userInfo.user.id);
      if (myGames) {
        for (let idx = 0; idx < myGames.length; idx++) {
          deleteGame(myGames[idx]);
        }
        io.emit("lobby_changed");
      }
    } else {
      socket.emit("user_not_entered");
    }
  };

  socket.on("create_game", function() {
    let userInfo = loggedUsers.userInfoBySocketID(socket.id);
    if (userInfo) {
      let game = games.createGame(userInfo);
      // Each game will have its own channel
      socket.join(game.gameID);
      // Notifications to the client
      socket.emit("game_created", game);
      io.emit("lobby_changed");
    } else {
      socket.emit("user_not_entered");
    }
  });

  socket.on("join_game", function(gameID) {
    let userInfo = loggedUsers.userInfoBySocketID(socket.id);
    if (userInfo) {
      let game = games.joinGame(gameID, userInfo);
      // Each game will have its own channel
      socket.join(game.gameID);
      // Notifications to the client
      socket.emit("game_joined", game);
      io.to(game.gameID).emit("game_changed", game);
      io.emit("lobby_changed");
    } else {
      socket.emit("user_not_entered");
    }
  });

  socket.on("remove_game", function(gameID) {
    let deletedGameClone = deleteGame(gameID);
    if (deletedGameClone) {
      io.emit("lobby_changed");
    }
  });

  socket.on("play", function(data) {
    let game = games.gameByID(data.gameID);
    if (game === null) {
      socket.emit("invalid_play", { type: "Invalid_Game", game: null });
      return;
    }
    var numPlayer = 0;
    if (game.player1 !== null && game.player1.socketID == socket.id) {
      numPlayer = 1;
    } else if (game.player2 !== null && game.player2.socketID == socket.id) {
      numPlayer = 2;
    }
    if (numPlayer === 0) {
      socket.emit("invalid_play", { type: "Invalid_Player", game: game });
      return;
    }
    if (game.play(numPlayer, data.index)) {
      io.to(game.gameID).emit("game_changed", game);
    } else {
      socket.emit("invalid_play", { type: "Invalid_Play", game: game });
      return;
    }
  });

  socket.on("get_my_activegames", function() {
    let userInfo = loggedUsers.userInfoBySocketID(socket.id);
    if (userInfo) {
      var my_games = games.allGamesOfUser(userInfo.user.id);
      socket.emit("my_active_games", my_games);
    } else {
      socket.emit("user_not_entered");
    }
  });

  socket.on("get_my_lobby_games", function() {
    let userInfo = loggedUsers.userInfoBySocketID(socket.id);
    if (userInfo) {
      var my_games = games.getLobbyGamesOf(userInfo.user.id);
      socket.emit("my_lobby_games", my_games);
    } else {
      socket.emit("user_not_entered");
    }
  });

  socket.on("disconnect", function() {
    deleteAllMyGames();
    console.log(
      "TicTacToe Player has disconnected (socket ID = " + socket.id + ")"
    );
  });

  socket.on('movement_created', movement => {
    if(movement) {
      io.sockets.emit()
    }
  })*/
});

// LIST OF ALL MESSAGES SENT FROM THE SERVER TO THE CLIENT:
//
// lobby_changed       - everyboby     - The list of games in the lobby has changed
// game_changed        - room / sender - Game state has changed / obtain the state of the game
// game_deleted        - room          - Game has been deleted
// game_created        - sender        - Game was created by me
// game_joined         - sender        - I have joined the game
// invalid_play        - sender        - The play I've made on the game is invalid
// my_active_games     - sender        - The list of my active games is ...
// my_lobby_games      - sender        - The list of my lobby games is ...
// user_not_entered    - sender        - The current user has not entered yet (has no session on "loggedUsers" list)

// LIST OF ALL MESSAGES RECEIVED ON THE SERVER (SENT FROM THE CLIENT TO THE SERVER)
//
// create_game         - User wants to create a new game
// join_game           - User wants to join a game (game is on the lobby)
// remove_game         - User wants to remove the game from the system (game state is indiferent
//                               user can be playing, waiting or terminated )
// play                - User has played on a specific game (clicks on a piece of the board)
// get_my_activegames  - User wants the list of all active games (games being played/waiting)
//                               list of games is "returned" by "my_active_games" message
// get_my_lobby_games  - User wants the list of all games on his lobby
//                               list of games on the lobbyy "returned" by "my_lobby_games" message
