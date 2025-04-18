
let user = "";
let computer = "";
let won = 0, lost = 0;

function res(val) {
  user = val;
  document.getElementById("choix-user").innerText = `👉 Tu as choisi : ${user}`;
}

function compute() {
  const options = ["Pierre", "Feuille", "Ciseau"];
  const rand = Math.floor(Math.random() * options.length);
  return options[rand];
}

function testing() {
  if (user === "") return;

  computer = compute();
  let resultat = "";

  if (user === computer) {
    resultat = "🟰 Égalité !";
  } else if (
    (user === "Pierre" && computer === "Ciseau") ||
    (user === "Feuille" && computer === "Pierre") ||
    (user === "Ciseau" && computer === "Feuille")
  ) {
    resultat = "✅ Gagné !";
    won++;
  } else {
    resultat = "❌ Perdu !";
    lost++;
  }

  document.getElementById("resultat").innerText =
    `🧑 Tu as joué ${user} — 🤖 L'ordi a joué ${computer}. Résultat : ${resultat}`;

  document.getElementById("score").innerText =
    `Gagné: ${won} fois. Perdu: ${lost} fois.`;

  // Reset le choix du joueur pour un nouveau tour
  user = "";
  document.getElementById("choix-user").innerText = "";
}
