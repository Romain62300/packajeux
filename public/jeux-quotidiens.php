<?php
session_start();
include_once __DIR__ . '/../includes/header.php';
?>

<main class="daily-hub-main">

  <div class="daily-hub-header">
    <h2 class="daily-hub-title">🗓️ Jeux Quotidiens</h2>
    <p class="daily-hub-subtitle">Gagnez des <strong>jetons virtuels</strong> chaque jour en jouant à nos jeux quotidiens !</p>
    <p class="daily-hub-rule">✅ Une seule partie par jeu par jour — revenez demain pour rejouer !</p>
  </div>

  <div class="daily-hub-grid">

    <a href="jeux-quotidiens/roue.php" class="daily-card daily-card--roue">
      <div class="daily-card-icon">🎡</div>
      <div class="daily-card-body">
        <h3>Roue de la Fortune</h3>
        <p>Faites tourner la roue et tentez de décrocher le jackpot !</p>
      </div>
      <span class="daily-card-badge">Jusqu'à 20 jetons</span>
    </a>

    <a href="jeux-quotidiens/smilegratt.php" class="daily-card daily-card--smile">
      <div class="daily-card-icon">😄</div>
      <div class="daily-card-body">
        <h3>SmileGratt'</h3>
        <p>Révèle 9 émojis et accumule les paires pour gagner !</p>
      </div>
      <span class="daily-card-badge">Jusqu'à 10 jetons</span>
    </a>

    <a href="jeux-quotidiens/vipgratt.php" class="daily-card daily-card--vip">
      <div class="daily-card-icon">👑</div>
      <div class="daily-card-body">
        <h3>Grattage VIP</h3>
        <p>Alignez 3 symboles identiques pour le grand lot !</p>
      </div>
      <span class="daily-card-badge daily-card-badge--gold">Jusqu'à 20 jetons</span>
    </a>

    <a href="jeux-quotidiens/ecogratt.php" class="daily-card daily-card--eco">
      <div class="daily-card-icon">🌳</div>
      <div class="daily-card-body">
        <h3>Grattage Écolo</h3>
        <p>Des symboles nature à révéler — trouvez les paires !</p>
      </div>
      <span class="daily-card-badge daily-card-badge--green">Jusqu'à 7 jetons</span>
    </a>

    <a href="jeux-quotidiens/miniloto.php" class="daily-card daily-card--loto">
      <div class="daily-card-icon">🎟️</div>
      <div class="daily-card-body">
        <h3>Mini Loterie</h3>
        <p>Choisissez 5 numéros parmi 20 et croisez les doigts !</p>
      </div>
      <span class="daily-card-badge daily-card-badge--blue">Jackpot 20 jetons</span>
    </a>

    <a href="jeux-quotidiens/coffre.php" class="daily-card daily-card--coffre">
      <div class="daily-card-icon">🔐</div>
      <div class="daily-card-body">
        <h3>Coffre au Trésor</h3>
        <p>Ouvrez un coffre parmi 5 — lequel cache la fortune ?</p>
      </div>
      <span class="daily-card-badge daily-card-badge--brown">Jusqu'à 8 jetons</span>
    </a>

    <a href="jeux-quotidiens/cochogratt.php" class="daily-card daily-card--cochon">
      <div class="daily-card-icon">🐷</div>
      <div class="daily-card-body">
        <h3>Cochogratt'</h3>
        <p>Grattez le ticket du cochon et découvrez votre gain !</p>
      </div>
      <span class="daily-card-badge">+1 jeton</span>
    </a>

  </div>

</main>

<style>
.daily-hub-main {
  max-width: 820px;
  margin: 0 auto;
  padding: 36px 20px 64px;
  font-family: 'Segoe UI', sans-serif;
}

.daily-hub-header { text-align: center; margin-bottom: 40px; }
.daily-hub-title {
  font-family: 'Poppins', sans-serif;
  font-size: 2.4rem;
  color: #2E3A59;
  margin-bottom: 10px;
}
.daily-hub-subtitle { color: #555; font-size: 1.05rem; margin-bottom: 8px; }
.daily-hub-rule { color: #888; font-size: .9rem; }

.daily-hub-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 20px;
}

.daily-card {
  display: flex; flex-direction: column;
  background: #fff; border-radius: 18px;
  border: 2px solid #e8ecf5;
  padding: 22px 20px;
  text-decoration: none; color: inherit;
  transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
  box-shadow: 0 3px 12px rgba(0,0,0,.07);
  position: relative; overflow: hidden;
}
.daily-card::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0; height: 4px;
  border-radius: 18px 18px 0 0;
}
.daily-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 36px rgba(0,0,0,.14);
}

.daily-card-icon { font-size: 2.8rem; margin-bottom: 12px; }
.daily-card-body h3 { font-size: 1.1rem; font-weight: 700; color: #2E3A59; margin: 0 0 6px; }
.daily-card-body p  { font-size: .88rem; color: #666; margin: 0; line-height: 1.5; }

.daily-card-badge {
  display: inline-block; margin-top: 14px;
  background: #f0f4ff; color: #2E3A59;
  padding: 5px 12px; border-radius: 20px;
  font-size: .8rem; font-weight: 600; width: fit-content;
}

/* Card accent colors */
.daily-card--roue::before   { background: linear-gradient(90deg,#ff6b6b,#ee5a24); }
.daily-card--roue:hover     { border-color: #ee5a24; }
.daily-card--smile::before  { background: linear-gradient(90deg,#ffd54f,#f9a825); }
.daily-card--smile:hover    { border-color: #f9a825; }
.daily-card--vip::before    { background: linear-gradient(90deg,#b8860b,#ffd700); }
.daily-card--vip:hover      { border-color: #b8860b; }
.daily-card--eco::before    { background: linear-gradient(90deg,#66bb6a,#2e7d32); }
.daily-card--eco:hover      { border-color: #4caf50; }
.daily-card--loto::before   { background: linear-gradient(90deg,#42a5f5,#1565c0); }
.daily-card--loto:hover     { border-color: #1565c0; }
.daily-card--coffre::before { background: linear-gradient(90deg,#a1887f,#6d4c41); }
.daily-card--coffre:hover   { border-color: #8d6e63; }
.daily-card--cochon::before { background: linear-gradient(90deg,#f48fb1,#e91e8c); }
.daily-card--cochon:hover   { border-color: #e91e8c; }

/* Badge variants */
.daily-card-badge--gold  { background: linear-gradient(135deg,#fff9c4,#ffd54f); color: #5d3a00; }
.daily-card-badge--green { background: #e8f5e9; color: #2e7d32; }
.daily-card-badge--blue  { background: #e3f2fd; color: #1565c0; }
.daily-card-badge--brown { background: #efebe9; color: #6d4c41; }

/* Dark mode */
body.dark-mode .daily-hub-title   { color: #ffd54f; }
body.dark-mode .daily-hub-subtitle { color: #bbb; }
body.dark-mode .daily-hub-rule    { color: #777; }
body.dark-mode .daily-card        { background: #1e1e2e; border-color: #333; }
body.dark-mode .daily-card-body h3 { color: #ffd54f; }
body.dark-mode .daily-card-body p  { color: #aaa; }
body.dark-mode .daily-card-badge   { background: #2a2a3a; color: #ccc; }

@media (max-width: 500px) {
  .daily-hub-title { font-size: 1.9rem; }
  .daily-hub-grid  { grid-template-columns: 1fr; }
}
</style>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
