<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config/config.php';

define('JEU_SMILE', 'smilegratt');
$SYMBOLS = ['😊','😂','🥰','😎','😜','🤩'];

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
    $chk->execute([$userId, JEU_SMILE, $today]);
    if ($chk->fetch()) {
        echo json_encode(['error' => 'already_played', 'message' => 'Vous avez déjà joué aujourd\'hui.']);
        exit;
    }

    // Génère grille 3×3 avec au moins une paire garantie
    $forced = $SYMBOLS[random_int(0, 5)];
    $grid   = [$forced, $forced];
    for ($i = 0; $i < 7; $i++) $grid[] = $SYMBOLS[random_int(0, 5)];
    shuffle($grid);

    $counts   = array_count_values($grid);
    $maxCount = max($counts);
    if ($maxCount >= 5)     $gain = 10;
    elseif ($maxCount >= 4) $gain = 5;
    elseif ($maxCount >= 3) $gain = 3;
    else                    $gain = 1; // paire toujours garantie

    try {
        $pdo->prepare("INSERT INTO jeux_quotidiens_log (utilisateur_id,jeu,date_jeu,gain) VALUES (?,?,?,?)")
            ->execute([$userId, JEU_SMILE, $today, $gain]);
        if ($gain > 0) $pdo->prepare("UPDATE utilisateurs SET jetons=jetons+? WHERE id=?")->execute([$gain, $userId]);
        $stJ = $pdo->prepare("SELECT jetons FROM utilisateurs WHERE id=?");
        $stJ->execute([$userId]);
        echo json_encode(['success' => true, 'grid' => $grid, 'gain' => $gain, 'jetons' => (int)$stJ->fetchColumn()]);
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
        $st->execute([$userId, JEU_SMILE, $today]);
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
<main class="smile-main">

  <div class="smile-header">
    <h2 class="smile-title">😄 SmileGratt'</h2>
    <p class="smile-subtitle">Révèle 9 émojis et accumule les paires pour gagner des jetons !</p>
    <?php if ($isLoggedIn): ?>
    <div class="smile-badge">💰 Mon solde&nbsp;: <strong id="jetonCount"><?= $userJetons ?></strong> jeton<?= $userJetons !== 1 ? 's' : '' ?></div>
    <?php endif; ?>
  </div>

  <?php if (!$isLoggedIn): ?>
  <div class="smile-login-prompt">
    <div class="smile-lock">🔐</div>
    <h3>Connexion requise</h3>
    <p>Créez un compte pour jouer chaque jour !</p>
    <div class="smile-login-btns">
      <a href="<?= $BASE_URL ?>/login.php" class="smile-btn smile-btn-primary">Se connecter</a>
      <a href="<?= $BASE_URL ?>/register.php" class="smile-btn smile-btn-secondary">Créer un compte</a>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($alreadyPlayed): ?>
  <div class="smile-played-banner">
    <?php if ($todayGain > 0): ?>
      <div class="smile-played-icon">🏆</div>
      <p>Vous avez gagné <strong><?= $todayGain ?> jeton<?= $todayGain > 1 ? 's' : '' ?></strong> aujourd'hui !</p>
    <?php else: ?>
      <div class="smile-played-icon">😔</div>
      <p>Pas de chance aujourd'hui… Revenez demain !</p>
    <?php endif; ?>
    <p class="smile-next-time">⏳ Prochain jeu&nbsp;: <strong><?= $hoursLeft ?>h <?= $minutesLeft ?>min</strong></p>
  </div>
  <?php elseif ($isLoggedIn): ?>

  <div class="smile-game">
    <div class="smile-grid" id="smileGrid">
      <?php for ($i = 0; $i < 9; $i++): ?>
      <div class="smile-cell" data-index="<?= $i ?>">
        <div class="smile-cell-inner">
          <div class="smile-cell-front">?</div>
          <div class="smile-cell-back"></div>
        </div>
      </div>
      <?php endfor; ?>
    </div>

    <button id="smilePlayBtn" class="smile-play-btn">
      <span class="smile-btn-icon">😄</span>
      <span>Révéler la carte !</span>
    </button>

    <div id="smileResult" class="smile-result" style="display:none;"></div>
  </div>

  <?php endif; ?>

  <div class="smile-legend">
    <h3>🏆 Tableau des gains</h3>
    <div class="smile-legend-grid">
      <div class="smile-legend-item"><span>2 identiques</span><strong>+1 jeton</strong></div>
      <div class="smile-legend-item"><span>3 identiques</span><strong>+3 jetons</strong></div>
      <div class="smile-legend-item"><span>4 identiques</span><strong>+5 jetons</strong></div>
      <div class="smile-legend-item"><span>5+ identiques</span><strong>+10 jetons</strong></div>
    </div>
  </div>

  <p class="smile-info">🗓️ Une seule partie par jour, par utilisateur connecté.</p>

</main>

<script>
const SMILE_CONFIG = {
  isLoggedIn:    <?= $isLoggedIn    ? 'true' : 'false' ?>,
  alreadyPlayed: <?= $alreadyPlayed ? 'true' : 'false' ?>,
};
</script>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
