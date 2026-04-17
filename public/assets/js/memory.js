const animaux = [
  "elephant.png",
  "girafe.png",
  "hippopotame.png",
  "lapin.png",
  "panda.png",
  "perroquet.png",
  "pingouin.png",
  "singe.png"
];

let cards = [];
let flippedCards = [];
let moves = 0;
let pairsFound = 0;
let startTime = null;
let timerInterval = null;
let lockBoard = false;

function initMemory() {
  const board = document.getElementById("memoryBoard");
  if (!board) return;

  // Duplique chaque animal (pour avoir des paires), puis mélange
  cards = [...animaux, ...animaux];
  cards.sort(() => Math.random() - 0.5);

  // Vide le plateau
  board.innerHTML = "";

  // Reset stats
  moves = 0;
  pairsFound = 0;
  flippedCards = [];
  lockBoard = false;
  document.getElementById("memoryMoves").textContent = "0";
  document.getElementById("memoryPairs").textContent = "0 / 8";
  document.getElementById("memoryTime").textContent = "0s";
  document.getElementById("memoryWin").style.display = "none";

  // Reset timer
  if (timerInterval) clearInterval(timerInterval);
  startTime = null;

  // Crée les 16 cartes
  cards.forEach((animal, index) => {
    const card = document.createElement("div");
    card.className = "memory-card";
    card.dataset.animal = animal;
    card.dataset.index = index;

    const img = document.createElement("img");
    img.src = `${MEMORY_BASE_URL}/assets/images/${animal}`;
    img.alt = animal.replace(".png", "");
    card.appendChild(img);

    card.addEventListener("click", () => flipCard(card));
    board.appendChild(card);
  });
}

function flipCard(card) {
  // On ignore si : plateau bloqué, carte déjà retournée, carte déjà trouvée
  if (lockBoard) return;
  if (card.classList.contains("flipped")) return;
  if (card.classList.contains("matched")) return;

  // Démarre le timer au premier clic
  if (startTime === null) {
    startTime = Date.now();
    timerInterval = setInterval(updateTimer, 1000);
  }

  card.classList.add("flipped");
  flippedCards.push(card);

  // Quand on a retourné 2 cartes, on vérifie
  if (flippedCards.length === 2) {
    moves++;
    document.getElementById("memoryMoves").textContent = moves;
    checkMatch();
  }
}

function checkMatch() {
  const [card1, card2] = flippedCards;
  const match = card1.dataset.animal === card2.dataset.animal;

  if (match) {
    card1.classList.add("matched");
    card2.classList.add("matched");
    pairsFound++;
    document.getElementById("memoryPairs").textContent = `${pairsFound} / 8`;
    flippedCards = [];

    if (pairsFound === 8) {
      endGame();
    }
  } else {
    // Pas de match : on retourne après 1 seconde
    lockBoard = true;
    setTimeout(() => {
      card1.classList.remove("flipped");
      card2.classList.remove("flipped");
      flippedCards = [];
      lockBoard = false;
    }, 1000);
  }
}

function updateTimer() {
  if (startTime === null) return;
  const elapsed = Math.floor((Date.now() - startTime) / 1000);
  document.getElementById("memoryTime").textContent = elapsed + "s";
}

function endGame() {
  clearInterval(timerInterval);
  const elapsed = Math.floor((Date.now() - startTime) / 1000);
  document.getElementById("winMoves").textContent = moves;
  document.getElementById("winTime").textContent = elapsed + "s";
  document.getElementById("memoryWin").style.display = "block";
}

function resetMemory() {
  initMemory();
}

// Lance le jeu au chargement de la page
document.addEventListener("DOMContentLoaded", initMemory);
