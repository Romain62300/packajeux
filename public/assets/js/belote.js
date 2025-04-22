document.addEventListener("DOMContentLoaded", () => {
  const startButton = document.querySelector("button");
  const modeSelect = document.getElementById("mode");
  const table = document.getElementById("tableBelote");

  startButton.addEventListener("click", () => {
    const mode = modeSelect.value;

    table.innerHTML = `
      <h2>Partie lancée en mode : ${mode === "annonces" ? "Avec annonces" : "Sans annonces"}</h2>
      <p>Distribution des cartes en cours...</p>
      <div class="cartes-dos">
        <img src="/gamepack/public/assets/images/cartes/bleu-foncé.png" alt="Dos de carte">
        <img src="/gamepack/public/assets/images/cartes/bleu-foncé.png" alt="Dos de carte">
        <img src="/gamepack/public/assets/images/cartes/bleu-foncé.png" alt="Dos de carte">
        <img src="/gamepack/public/assets/images/cartes/bleu-foncé.png" alt="Dos de carte">
      </div>
    `;
  });
});
