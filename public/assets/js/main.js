
// 🎨 Basculer le mode sombre (icône seule 🌙 / ☀️)
const toggle = document.getElementById('darkModeToggle');
if (toggle) {
  const body = document.body;

  function updateIcon() {
    toggle.textContent = body.classList.contains('dark-mode') ? "☀️" : "🌙";
  }

  if (localStorage.getItem('darkMode') === 'enabled') {
    body.classList.add('dark-mode');
  }

  updateIcon();

  toggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
    updateIcon();
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

// ⬇️ Dropdown Jeux
document.addEventListener("DOMContentLoaded", () => {
  const dropdown = document.querySelector(".dropdown");
  const submenu = dropdown?.querySelector(".dropdown-content");

  dropdown?.addEventListener("click", (e) => {
    e.stopPropagation();
    submenu?.classList.toggle("active");
  });

  document.addEventListener("click", () => {
    submenu?.classList.remove("active");
  });
});
