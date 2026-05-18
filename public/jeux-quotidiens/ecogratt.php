<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config/config.php';

define('JEU_ECO', 'ecogratt');
$SYMBOLS = ['🌸','🌿','🍀','🌳','🦋','🌻'];

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
    $chk->execute([$userId, JEU_ECO, $today]);
    if ($chk->fetch()) {
        echo json_encode(['error' => 'already_played', 'message' => 'Vous avez déjà joué aujourd\'hui.']);
        exit;
    }

    // 4 cases : compter les paires/triplets
    $grid = [];
    for ($i = 0; $i < 4; $i++) $grid[] = $SYMBOLS[random_int(0, 5)];

    $counts   = array_count_values($grid);
    $maxCount = max($counts);
    if ($maxCount >= 4)     $gain = 7;
    elseif ($maxCount >= 3) $gain = 3;
    elseif ($maxCount >= 2) $gain = 1;
    else                    $gain = 0;

    try {
        $pdo->prepare("INSERT INTO jeux_quotidiens_log (utilisateur_id,jeu,date_jeu,gain) VALUES (?,?,?,?)")
            ->execute([$userId, JEU_ECO, $today, $gain]);
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
        $st->execute([$userId, JEU_ECO, $today]);
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
<main class="eco-main">

  <div class="eco-header">
    <h2 class="eco-title">🌳 Grattage Écolo</h2>
    <p class="eco-subtitle">Révèle 4 symboles nature et trouve les paires gagnantes !</p>
    <?php if ($isLoggedIn): ?>
    <div class="eco-badge">💰 Mon solde&nbsp;: <strong id="jetonCount"><?= $userJetons ?></strong> jeton<?= $userJetons !== 1 ? 's' : '' ?></div>
    <?php endif; ?>
  </div>

  <?php if (!$isLoggedIn): ?>
  <div class="eco-login-prompt">
    <div class="eco-lock">🔐</div>
    <h3>Connexion requise</h3>
    <p>Connectez-vous pour jouer chaque jour !</p>
    <div class="eco-login-btns">
      <a href="<?= $BASE_URL ?>/login.php" class="eco-btn eco-btn-primary">Se connecter</a>
      <a href="<?= $BASE_URL ?>/register.php" class="eco-btn eco-btn-secondary">Créer un compte</a>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($alreadyPlayed): ?>
  <div class="eco-played-banner">
    <?php if ($todayGain > 0): ?>
      <div class="eco-played-icon">🌻</div>
      <p>Vous avez gagné <strong><?= $todayGain ?> jeton<?= $todayGain > 1 ? 's' : '' ?></strong> aujourd'hui !</p>
    <?php else: ?>
      <div class="eco-played-icon">🍂</div>
      <p>Pas de chance aujourd'hui… La nature sera plus généreuse demain !</p>
    <?php endif; ?>
    <p class="eco-next-time">⏳ Prochain grattage&nbsp;: <strong><?= $hoursLeft ?>h <?= $minutesLeft ?>min</strong></p>
  </div>
  <?php elseif ($isLoggedIn): ?>

  <div class="eco-game">
    <div class="eco-leaf-deco eco-leaf-1">🍃</div>
    <div class="eco-leaf-deco eco-leaf-2">🌿</div>

    <div class="eco-grid" id="ecoGrid">
      <?php for ($i = 0; $i < 4; $i++): ?>
      <div class="eco-cell" data-index="<?= $i ?>">
        <div class="eco-cell-inner">
          <div class="eco-cell-front">🌱</div>
          <div class="eco-cell-back"></div>
        </div>
      </div>
      <?php endfor; ?>
    </div>

    <button id="ecoPlayBtn" class="eco-play-btn">
      <span class="eco-btn-icon">🌿</span>
      <span>Révéler les symboles !</span>
    </button>

    <div id="ecoResult" class="eco-result" style="display:none;"></div>
  </div>

  <?php endif; ?>

  <div class="eco-legend">
    <h3>🌟 Tableau des gains</h3>
    <div class="eco-legend-grid">
      <div class="eco-legend-item"><span>2 identiques</span><strong>+1 jeton</strong></div>
      <div class="eco-legend-item"><span>3 identiques</span><strong>+3 jetons</strong></div>
      <div class="eco-legend-item"><span>4 identiques</span><strong>+7 jetons</strong></div>
    </div>
  </div>

  <p class="eco-info">🗓️ Une seule partie par jour, par utilisateur connecté.</p>

</main>

<script>
const ECO_CONFIG = {
  isLoggedIn:    <?= $isLoggedIn    ? 'true' : 'false' ?>,
  alreadyPlayed: <?= $alreadyPlayed ? 'true' : 'false' ?>,
};
</script>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
