/*jshint esversion: 6 */

class TicTacToeGame {
  constructor(ID, userInfo) {
    this.gameID = ID;
    this.gameEnded = false;
    this.gameStarted = false;
    this.player1 = userInfo;
    this.player2 = null;
    this.playerTurn = 1;
    this.winner = 0;
    this.board = [0, 0, 0, 0, 0, 0, 0, 0, 0];
  }

  join(user2Info) {
    this.player2 = user2Info;
    this.gameStarted = true;
  }

  hasRow(value) {
    return (
      (this.board[0] == value &&
        this.board[1] == value &&
        this.board[2] == value) ||
      (this.board[3] == value &&
        this.board[4] == value &&
        this.board[5] == value) ||
      (this.board[6] == value &&
        this.board[7] == value &&
        this.board[8] == value) ||
      (this.board[0] == value &&
        this.board[3] == value &&
        this.board[6] == value) ||
      (this.board[1] == value &&
        this.board[4] == value &&
        this.board[7] == value) ||
      (this.board[2] == value &&
        this.board[5] == value &&
        this.board[8] == value) ||
      (this.board[0] == value &&
        this.board[4] == value &&
        this.board[8] == value) ||
      (this.board[2] == value &&
        this.board[4] == value &&
        this.board[6] == value)
    );
  }

  checkGameEnded() {
    if (this.hasRow(1)) {
      this.winner = 1;
      this.gameEnded = true;
      return true;
    } else if (this.hasRow(2)) {
      this.winner = 2;
      this.gameEnded = true;
      return true;
    } else if (this.isBoardComplete()) {
      this.winner = 0;
      this.gameEnded = true;
      return true;
    }
    return false;
  }

  isBoardComplete() {
    for (let value of this.board) {
      if (value === 0) {
        return false;
      }
    }
    return true;
  }

  play(playerNumber, index) {
    if (!this.gameStarted) {
      return false;
    }
    if (this.gameEnded) {
      return false;
    }
    if (playerNumber != this.playerTurn) {
      return false;
    }
    if (this.board[index] !== 0) {
      return false;
    }
    this.board[index] = playerNumber;
    if (!this.checkGameEnded()) {
      this.playerTurn = this.playerTurn == 1 ? 2 : 1;
    }
    return true;
  }
}

module.exports = TicTacToeGame;
