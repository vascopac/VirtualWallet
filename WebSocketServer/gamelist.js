/*jshint esversion: 6 */

var TicTacToeGame = require("./gamemodel.js");

class GameList {
  constructor() {
    this.contadorID = 0;
    this.games = new Map();
  }

  gameByID(gameID) {
    return this.games.get(gameID);
  }

  createGame(userInfo) {
    this.contadorID = this.contadorID + 1;
    var game = new TicTacToeGame(this.contadorID, userInfo);
    this.games.set(game.gameID, game);
    return game;
  }

  joinGame(gameID, userInfo) {
    let game = this.gameByID(gameID);
    if (!game) {
      return null;
    }
    game.join(userInfo);
    return game;
  }

  // Removes the game (with provided gameID) from the list of games
  // returns a copy of the deleted game, or null if game was not deleted
  removeGame(gameID) {
    let game = this.gameByID(gameID);
    if (!game) {
      return null;
    }
    let gameClone = {};
    Object.assign(gameClone, game);
    this.games.delete(gameID);
    return gameClone;
  }

  // return an array with all games IDs (gameID) associated to the user
  allGameIDsOfUser(userID) {
    let games = [];
    for (var [key, value] of this.games) {
      if (
        (value.player1 !== null && value.player1.user.id == userID) ||
        (value.player2 !== null && value.player2.user.id == userID)
      ) {
        games.push(value.gameID);
      }
    }
    return games;
  }

  // return an array with all games associated to the user
  allGamesOfUser(userID) {
    let games = [];
    for (var [key, value] of this.games) {
      if (
        (value.player1 !== null && value.player1.user.id == userID) ||
        (value.player2 !== null && value.player2.user.id == userID)
      ) {
        games.push(value);
      }
    }
    return games;
  }

  // Array with all games that a specific user can view on the lobby
  // Game has not started nor ended
  // User is not player 1
  // Player 2 is null
  // For each game, it only returns the gameID and player1 (ID and Name).
  // All remaing information is not required and would make messages heavier
  getLobbyGamesOf(userID) {
    let games = [];
    for (var [key, value] of this.games) {
      if (!value.gameStarted && !value.gameEnded) {
        if (
          value.player1 !== null &&
          value.player1.user.id != userID &&
          value.player2 === null
        ) {
          games.push({
            gameID: value.gameID,
            player1: {
              id: value.player1.user.id,
              name: value.player1.user.name
            }
          });
        }
      }
    }
    return games;
  }
}

module.exports = GameList;
