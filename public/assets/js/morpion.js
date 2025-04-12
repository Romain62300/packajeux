let currentPlayer = "X";
let board = Array(9).fill("");
let gameActive = true;

const cells = document.querySelectorAll(".cell");
const turnInfo = document.getElementById("turnInfo");

function updateTurnInfo() {
  turnInfo.textContent = `Tour de : ${currentPlayer}`;
}

function checkWin() {
  const winPatterns = [
    [0, 1, 2], [3, 4, 5], [6, 7, 8],
    [0, 3, 6], [1, 4, 7], [2, 5, 8],
    [0, 4, 8], [2, 4, 6]
  ];

  return winPatterns.some(pattern =>
    pattern.every(index => board[index] === currentPlayer)
  );
}

function handleClick(e) {
  const index = e.target.getAttribute("data-index");

  if (board[index] === "" && gameActive) {
    board[index] = currentPlayer;
    e.target.textContent = currentPlayer;
    e.target.style.color = currentPlayer === "X" ? "#e74c3c" : "#3498db";

    if (checkWin()) {
      turnInfo.textContent = `Le joueur ${currentPlayer} a gagné ! 🎉`;
      gameActive = false;
    } else if (!board.includes("")) {
      turnInfo.textContent = "Match nul ! 🤝";
      gameActive = false;
    } else {
      currentPlayer = currentPlayer === "X" ? "O" : "X";
      updateTurnInfo();
    }
  }
}

function resetGame() {
  board = Array(9).fill("");
  gameActive = true;
  currentPlayer = "X";
  updateTurnInfo();
  cells.forEach(cell => {
    cell.textContent = "";
    cell.style.color = "#000";
  });
}

cells.forEach(cell => {
  cell.addEventListener("click", handleClick);
});

updateTurnInfo();
