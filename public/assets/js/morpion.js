let currentPlayer = "X";
let board = Array(9).fill("");
let gameActive = true;

const cells = document.querySelectorAll(".cell");
const turnInfo = document.getElementById("turnInfo");
const clickSound = new Audio("/Packajeux/public/assets/sounds/click.mp3");

function updateTurnInfo() {
  turnInfo.textContent = `Tour de : ${currentPlayer}`;
}

function checkWin() {
  const winPatterns = [
    [0, 1, 2], [3, 4, 5], [6, 7, 8],
    [0, 3, 6], [1, 4, 7], [2, 5, 8],
    [0, 4, 8], [2, 4, 6]
  ];

  for (const pattern of winPatterns) {
    const [a, b, c] = pattern;
    if (
      board[a] !== "" &&
      board[a] === board[b] &&
      board[a] === board[c]
    ) {
      highlightWinningCells(pattern);
      return true;
    }
  }
  return false;
}

function highlightWinningCells(pattern) {
  pattern.forEach(index => {
    cells[index].classList.add("glow");
    cells[index].style.backgroundColor = "#ffe066"; // jaune clair
  });

  const emoji = currentPlayer === "X" ? "❌" : "⭕️";
  turnInfo.textContent = `${emoji} a gagné ! 🎉`;

  const audio = new Audio("/Packajeux/public/assets/sounds/victory.mp3");
  audio.play();

  // 🎊 Confettis
  confetti({
    particleCount: 150,
    spread: 80,
    origin: { y: 0.6 }
  });
}

function handleClick(e) {
  const index = e.target.getAttribute("data-index");

  if (board[index] === "" && gameActive) {
    clickSound.play(); // 🔊 Effet clic
    board[index] = currentPlayer;
    e.target.textContent = currentPlayer;
    e.target.style.color = currentPlayer === "X" ? "#e74c3c" : "#3498db";

    if (checkWin()) {
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
    cell.classList.remove("glow");
    cell.style.backgroundColor = "#f2f2f2";
  });
}

cells.forEach(cell => {
  cell.addEventListener("click", handleClick);

  // 🎨 Ajout effet visuel au survol
  cell.addEventListener("mouseenter", () => {
    if (cell.textContent === "" && gameActive) {
      cell.style.boxShadow = "0 0 10px rgba(255, 213, 79, 0.5)";
    }
  });
  cell.addEventListener("mouseleave", () => {
    cell.style.boxShadow = "none";
  });
});

updateTurnInfo();
