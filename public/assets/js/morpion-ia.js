const cells = document.querySelectorAll(".cell");
const turnInfo = document.getElementById("turnInfo");

let board = Array(9).fill("");
let currentPlayer = "X";
let gameOver = false;

function resetGame() {
  board = Array(9).fill("");
  gameOver = false;
  currentPlayer = "X";
  updateTurnInfo();
  cells.forEach(cell => {
    cell.textContent = "";
    cell.classList.remove("x", "o", "glow");
    cell.style.backgroundColor = "#f2f2f2";
  });
  if (currentPlayer === "O") playIA();
}

function updateTurnInfo() {
  turnInfo.textContent = `Tour de : ${currentPlayer}`;
}

cells.forEach((cell, index) => {
  cell.addEventListener("click", () => {
    if (board[index] !== "" || gameOver || currentPlayer !== "X") return;

    board[index] = "X";
    cell.textContent = "X";
    cell.classList.add("x");

    if (checkWin("X")) {
      endGame("X");
    } else if (!board.includes("")) {
      drawGame();
    } else {
      currentPlayer = "O";
      updateTurnInfo();
      setTimeout(playIA, 400);
    }
  });
});

function playIA() {
  const bestMove = getBestMove(board);
  if (bestMove !== -1) {
    board[bestMove] = "O";
    cells[bestMove].textContent = "O";
    cells[bestMove].classList.add("o");
  }

  if (checkWin("O")) {
    endGame("O");
  } else if (!board.includes("")) {
    drawGame();
  } else {
    currentPlayer = "X";
    updateTurnInfo();
  }
}

function getBestMove(boardState) {
  let bestScore = -Infinity;
  let move = -1;

  for (let i = 0; i < 9; i++) {
    if (boardState[i] === "") {
      boardState[i] = "O";
      let score = minimax(boardState, 0, false);
      boardState[i] = "";
      if (score > bestScore) {
        bestScore = score;
        move = i;
      }
    }
  }

  return move;
}

function minimax(boardState, depth, isMaximizing) {
  if (checkWinStatic(boardState, "O")) return 10 - depth;
  if (checkWinStatic(boardState, "X")) return depth - 10;
  if (!boardState.includes("")) return 0;

  if (isMaximizing) {
    let best = -Infinity;
    for (let i = 0; i < 9; i++) {
      if (boardState[i] === "") {
        boardState[i] = "O";
        let score = minimax(boardState, depth + 1, false);
        boardState[i] = "";
        best = Math.max(best, score);
      }
    }
    return best;
  } else {
    let best = Infinity;
    for (let i = 0; i < 9; i++) {
      if (boardState[i] === "") {
        boardState[i] = "X";
        let score = minimax(boardState, depth + 1, true);
        boardState[i] = "";
        best = Math.min(best, score);
      }
    }
    return best;
  }
}

function checkWin(player) {
  const winPatterns = [
    [0, 1, 2], [3, 4, 5], [6, 7, 8],
    [0, 3, 6], [1, 4, 7], [2, 5, 8],
    [0, 4, 8], [2, 4, 6]
  ];

  for (const pattern of winPatterns) {
    const [a, b, c] = pattern;
    if (board[a] === player && board[b] === player && board[c] === player) {
      pattern.forEach(index => {
        cells[index].classList.add("glow");
        cells[index].style.backgroundColor = "#ffe066";
      });
      return true;
    }
  }
  return false;
}

function checkWinStatic(state, player) {
  const winPatterns = [
    [0, 1, 2], [3, 4, 5], [6, 7, 8],
    [0, 3, 6], [1, 4, 7], [2, 5, 8],
    [0, 4, 8], [2, 4, 6]
  ];
  return winPatterns.some(p => p.every(i => state[i] === player));
}

function endGame(winner) {
  const emoji = winner === "X" ? "❌" : "⭕️";
  turnInfo.textContent = `${emoji} a gagné ! 🎉`;
  gameOver = true;
  const audio = new Audio("/Packajeux/public/assets/sounds/victory.mp3");
  audio.play();
  confetti({
    particleCount: 80,
    spread: 70,
    origin: { y: 0.5 }
  });
}

function drawGame() {
  turnInfo.textContent = "Match nul ! 🤝";
  gameOver = true;
}
