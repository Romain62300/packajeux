document.addEventListener("DOMContentLoaded", () => {
  const secretNumber = Math.floor(Math.random() * 100) + 1;
  let attempts = 0;

  const guessInput = document.getElementById("guess");
  const submitBtn = document.getElementById("submitGuess");
  const feedback = document.getElementById("feedback");
  const attemptsDisplay = document.getElementById("attempts");
  const restartBtn = document.getElementById("restart");

  function checkGuess() {
    const userGuess = parseInt(guessInput.value);
    if (isNaN(userGuess) || userGuess < 1 || userGuess > 100) {
      feedback.innerHTML = "⛔ Entrez un nombre entre 1 et 100.";
      return;
    }

    attempts++;
    if (userGuess === secretNumber) {
      feedback.innerHTML = `🎉 Bravo ! Le nombre était ${secretNumber}.`;
      attemptsDisplay.innerHTML = `Vous avez réussi en ${attempts} tentative(s).`;
      guessInput.disabled = true;
      submitBtn.disabled = true;
      restartBtn.style.display = "inline-block";
    } else if (userGuess < secretNumber) {
      feedback.innerHTML = "🔺 Trop bas !";
    } else {
      feedback.innerHTML = "🔻 Trop haut !";
    }

    guessInput.value = "";
    guessInput.focus();
  }

  submitBtn.addEventListener("click", checkGuess);
  guessInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
      checkGuess();
    }
  });

  restartBtn.addEventListener("click", () => {
    location.reload(); // recharge la page pour recommencer
  });
});
