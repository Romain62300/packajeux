<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config/config.php';

define('JEU_COCHON', 'cochogratt');

$isLoggedIn = !empty($_SESSION['utilisateur']);
$userId     = $isLoggedIn ? (int)($_SESSION['utilisateur']['id'] ?? 0) : 0;

if ($userId > 0) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `jeux_quotidiens_log` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `utilisateur_id` INT NOT NULL,
        `jeu` VARCHAR(50) NOT NULL,
        `date_jeu` DATE NOT NULL,
        `gain` INT DEFAULT 0,
        `segment` TINYINT DEFAULT NULL,
        UNIQUE KEY `unique_daily` (`utilisateur_id`,`jeu`,`date_jeu`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

if ($isLoggedIn && $userId > 0
    && $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'play'
) {
    header('Content-Type: application/json; charset=utf-8');
    $today = date('Y-m-d');
    $chk = $pdo->prepare("SELECT id FROM jeux_quotidiens_log WHERE utilisateur_id=? AND jeu=? AND date_jeu=?");
    $chk->execute([$userId, JEU_COCHON, $today]);
    if ($chk->fetch()) {
        echo json_encode(['error' => 'already_played', 'message' => 'Vous avez déjà joué aujourd\'hui.']);
        exit;
    }

    $pool = [0, 0, 0, 0, 1, 1, 1, 2, 2, 3, 5];
    $gain = $pool[array_rand($pool)];

    try {
        $pdo->prepare("INSERT INTO jeux_quotidiens_log (utilisateur_id,jeu,date_jeu,gain) VALUES (?,?,?,?)")
            ->execute([$userId, JEU_COCHON, $today, $gain]);
        if ($gain > 0) $pdo->prepare("UPDATE utilisateurs SET jetons=jetons+? WHERE id=?")->execute([$gain, $userId]);
        $stJ = $pdo->prepare("SELECT jetons FROM utilisateurs WHERE id=?");
        $stJ->execute([$userId]);
        echo json_encode(['success' => true, 'gain' => $gain, 'jetons' => (int)$stJ->fetchColumn()]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'already_played', 'message' => 'Déjà joué aujourd\'hui.']);
    }
    exit;
}

include_once __DIR__ . '/../../includes/header.php';

$alreadyPlayed = false;
$todayGain     = null;
$userJetons    = 0;

if ($userId > 0) {
    $today = date('Y-m-d');
    try {
        $st = $pdo->prepare("SELECT gain FROM jeux_quotidiens_log WHERE utilisateur_id=? AND jeu=? AND date_jeu=?");
        $st->execute([$userId, JEU_COCHON, $today]);
        if ($row = $st->fetch(PDO::FETCH_ASSOC)) { $alreadyPlayed = true; $todayGain = (int)$row['gain']; }
    } catch (Exception $e) {}
    try {
        $stJ = $pdo->prepare("SELECT jetons FROM utilisateurs WHERE id=?");
        $stJ->execute([$userId]);
        $userJetons = (int)$stJ->fetchColumn();
    } catch (Exception $e) {}
}

$secsLeft    = strtotime('tomorrow') - time();
$hoursLeft   = floor($secsLeft / 3600);
$minutesLeft = floor(($secsLeft % 3600) / 60);
?>
<main class="cochon-main">

  <div class="cochon-header">
    <h2 class="cochon-title">🐷 Cochogratt'</h2>
    <p class="cochon-subtitle">Grattez la zone pour découvrir votre gain du jour !</p>
    <?php if ($isLoggedIn): ?>
    <div class="cochon-badge">💰 Mon solde&nbsp;: <strong id="jetonCount"><?= $userJetons ?></strong> jeton<?= $userJetons !== 1 ? 's' : '' ?></div>
    <?php endif; ?>
  </div>

  <?php if (!$isLoggedIn): ?>
  <div class="cochon-login-prompt">
    <div class="cochon-lock">🔐</div>
    <h3>Connexion requise</h3>
    <p>Connectez-vous pour gratter le ticket quotidien !</p>
    <div class="cochon-login-btns">
      <a href="<?= $BASE_URL ?>/login.php" class="cochon-btn cochon-btn-primary">Se connecter</a>
      <a href="<?= $BASE_URL ?>/register.php" class="cochon-btn cochon-btn-secondary">Créer un compte</a>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($alreadyPlayed): ?>
  <div class="cochon-played-banner">
    <?php if ($todayGain > 0): ?>
      <div class="cochon-played-icon">🏆</div>
      <p>Vous avez gagné <strong><?= $todayGain ?> jeton<?= $todayGain > 1 ? 's' : '' ?></strong> aujourd'hui !</p>
    <?php else: ?>
      <div class="cochon-played-icon">🐷</div>
      <p>Pas de chance aujourd'hui… Revenez demain !</p>
    <?php endif; ?>
    <p class="cochon-next-time">⏳ Prochain grattage&nbsp;: <strong><?= $hoursLeft ?>h <?= $minutesLeft ?>min</strong></p>
  </div>
  <?php elseif ($isLoggedIn): ?>

  <div class="cochon-game">
    <div class="cochon-ticket">
      <div class="cochon-ticket-top">
        <span class="cochon-ticket-label">🐷 TICKET DU JOUR 🐷</span>
      </div>

      <div class="cochon-scratch-wrap" id="scratchWrap">
        <div class="cochon-reveal" id="cochonReveal">
          <span id="revealText">…</span>
        </div>
        <canvas id="scratchCanvas"></canvas>
        <p class="cochon-hint" id="scratchHint">👆 Grattez ici !</p>
      </div>

      <div class="cochon-ticket-bottom">Un seul grattage par jour</div>
    </div>

    <div id="cochonResult" class="cochon-result" style="display:none;"></div>
  </div>

  <?php endif; ?>

  <div class="cochon-legend">
    <h3>🏆 Gains possibles</h3>
    <div class="cochon-legend-grid">
      <div class="cochon-legend-item">Rien</div>
      <div class="cochon-legend-item">+1 jeton</div>
      <div class="cochon-legend-item">+2 jetons</div>
      <div class="cochon-legend-item">+3 jetons</div>
      <div class="cochon-legend-item">+5 jetons</div>
    </div>
  </div>

  <p class="cochon-info">🗓️ Un seul grattage par jour, par utilisateur connecté.</p>

</main>

<script>
const COCHON_CONFIG = {
  isLoggedIn:    <?= $isLoggedIn    ? 'true' : 'false' ?>,
  alreadyPlayed: <?= $alreadyPlayed ? 'true' : 'false' ?>,
};
</script>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
