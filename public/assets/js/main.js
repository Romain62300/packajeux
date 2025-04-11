// 🎨 Basculer le mode sombre
const toggle = document.getElementById('darkModeToggle');
if (toggle) {
  const body = document.body;

  // ➕ Fonction pour mettre à jour le texte du bouton
  function updateToggleText() {
    toggle.textContent = body.classList.contains('dark-mode') ? "☀️ Mode clair" : "🌙 Mode sombre";
  }

  // Appliquer le thème stocké
  if (localStorage.getItem('darkMode') === 'enabled') {
    body.classList.add('dark-mode');
  }

  updateToggleText(); // mettre le bon texte au chargement

  toggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
    updateToggleText(); // mettre à jour le texte après clic
  });
}

// ⬆️ Bouton retour en haut
const topBtn = document.getElementById("backToTop");
if (topBtn) {
  window.onscroll = () => {
    topBtn.style.display = (document.documentElement.scrollTop > 100) ? "block" : "none";
  };

  topBtn.onclick = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };
}
// 🍔 Menu burger
const burger = document.getElementById('burger');
const navLinks = document.getElementById('nav-links');

if (burger && navLinks) {
  burger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
  });
}
