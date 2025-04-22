document.addEventListener("DOMContentLoaded", () => {
  const secretNumber = Math.floor(Math.random() * 100) + 1;
  let attempts = 0;

  const guessInput = document.getElementById("guess");
  const submitBtn = document.getElementById("submitGuess");
  const feedback = document.getElementById("feedback");
  const attemptsText = document.getElementById("attempts");
  const restartBtn = document.getElementById("restart");

  function handleGuess() {
    const userGuess = parseInt(guessInput.value);
    if (isNaN(userGuess) || userGuess < 1 || userGuess > 100) {
      feedback.textContent = "⛔ Entrez un nombre entre 1 et 100.";
      return;
    }

    attempts++;
    if (userGuess < secretNumber) {
      feedback.textContent = "🔺 Trop bas !";
    } else if (userGuess > secretNumber) {
      feedback.textContent = "🔻 Trop haut !";
    } else {
      feedback.innerHTML = `🎉 Bravo ! Le nombre était <strong>${secretNumber}</strong>.`;
      attemptsText.innerHTML = `✅ Trouvé en <strong>${attempts}</strong> tentative(s).`;
      submitBtn.disabled = true;
      guessInput.disabled = true;
      restartBtn.style.display = "inline-block";
    }

    guessInput.value = "";
    guessInput.focus();
  }

  submitBtn.addEventListener("click", handleGuess);

  guessInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") handleGuess();
  });

  restartBtn.addEventListener("click", () => {
    location.reload();
  });
});
