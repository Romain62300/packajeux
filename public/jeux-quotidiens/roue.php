<?php
// Session + config AVANT tout output (nécessaire pour le handler AJAX)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/config.php';

$segments = [
    ['label' => 'Rien',       'gain' => 0,  'weight' => 30],
    ['label' => '+1 jeton',   'gain' => 1,  'weight' => 25],
    ['label' => '+2 jetons',  'gain' => 2,  'weight' => 20],
    ['label' => '+3 jetons',  'gain' => 3,  'weight' => 15],
    ['label' => '+5 jetons',  'gain' => 5,  'weight' => 10],
    ['label' => '+7 jetons',  'gain' => 7,  'weight' => 5],
    ['label' => '+10 jetons', 'gain' => 10, 'weight' => 3],
    ['label' => 'JACKPOT!',   'gain' => 20, 'weight' => 1],
];

$isLoggedIn = !empty($_SESSION['utilisateur']);
$userId     = $isLoggedIn ? (int)($_SESSION['utilisateur']['id'] ?? 0) : 0;

if ($userId > 0) {
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS `jeux_quotidiens_log` (
            `id`             INT AUTO_INCREMENT PRIMARY KEY,
            `utilisateur_id` INT NOT NULL,
            `jeu`            VARCHAR(50) NOT NULL,
            `date_jeu`       DATE NOT NULL,
            `gain`           INT DEFAULT 0,
            `segment`        TINYINT DEFAULT NULL,
            UNIQUE KEY `unique_daily` (`utilisateur_id`, `jeu`, `date_jeu`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );
}

// ── AJAX spin — AVANT include header.php pour ne pas polluer la réponse JSON ──
if ($isLoggedIn && $userId > 0
    && $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'spin'
) {
    header('Content-Type: application/json; charset=utf-8');

    $today = date('Y-m-d');
    $chk   = $pdo->prepare("SELECT id FROM jeux_quotidiens_log WHERE utilisateur_id = ? AND jeu = 'roue' AND date_jeu = ?");
    $chk->execute([$userId, $today]);
    if ($chk->fetch()) {
        echo json_encode(['error' => 'already_played', 'message' => 'Vous avez déjà joué aujourd\'hui.']);
        exit;
    }

    $totalWeight = array_sum(array_column($segments, 'weight'));
    $rand  = random_int(1, $totalWeight);
    $cumul = 0;
    $winIdx = 0;
    foreach ($segments as $i => $seg) {
        $cumul += $seg['weight'];
        if ($rand <= $cumul) { $winIdx = $i; break; }
    }
    $gain  = $segments[$winIdx]['gain'];
    $label = $segments[$winIdx]['label'];

    try {
        $ins = $pdo->prepare("INSERT INTO jeux_quotidiens_log (utilisateur_id, jeu, date_jeu, gain, segment) VALUES (?, 'roue', ?, ?, ?)");
        $ins->execute([$userId, $today, $gain, $winIdx]);

        if ($gain > 0) {
            $pdo->prepare("UPDATE utilisateurs SET jetons = jetons + ? WHERE id = ?")->execute([$gain, $userId]);
        }

        $stmtJ = $pdo->prepare("SELECT jetons FROM utilisateurs WHERE id = ?");
        $stmtJ->execute([$userId]);
        $newJetons = (int)$stmtJ->fetchColumn();

        echo json_encode(['success' => true, 'segment' => $winIdx, 'gain' => $gain, 'label' => $label, 'jetons' => $newJetons]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'already_played', 'message' => 'Vous avez déjà joué aujourd\'hui.']);
    }
    exit;
}

// ── HTML page — header inclus seulement pour les requêtes normales ─────────────
include_once __DIR__ . '/../../includes/header.php';

// ── Page state ────────────────────────────────────────────────────────────────
$alreadyPlayed = false;
$todayGain     = null;
$todaySegment  = null;
$userJetons    = 0;

if ($userId > 0) {
    $today = date('Y-m-d');
    try {
        $stmtRow = $pdo->prepare("SELECT gain, segment FROM jeux_quotidiens_log WHERE utilisateur_id = ? AND jeu = 'roue' AND date_jeu = ?");
        $stmtRow->execute([$userId, $today]);
        $rowData = $stmtRow->fetch(PDO::FETCH_ASSOC);
        if ($rowData) {
            $alreadyPlayed = true;
            $todayGain     = (int)$rowData['gain'];
            $todaySegment  = (int)$rowData['segment'];
        }
    } catch (Exception $e) {}

    try {
        $stJ = $pdo->prepare("SELECT jetons FROM utilisateurs WHERE id = ?");
        $stJ->execute([$userId]);
        $userJetons = (int)$stJ->fetchColumn();
    } catch (Exception $e) {}
}

$secsLeft    = strtotime('tomorrow') - time();
$hoursLeft   = floor($secsLeft / 3600);
$minutesLeft = floor(($secsLeft % 3600) / 60);
?>

<main class="roue-main">

  <div class="roue-header">
    <h2 class="roue-title">🎡 Roue de la Fortune</h2>
    <p class="roue-subtitle">Tentez votre chance chaque jour et gagnez des jetons !</p>
    <?php if ($isLoggedIn): ?>
    <div class="roue-jetons-badge">
      💰 Mon solde&nbsp;: <strong id="jetonCount"><?= $userJetons ?></strong> jeton<?= $userJetons !== 1 ? 's' : '' ?>
    </div>
    <?php endif; ?>
  </div>

  <?php if (!$isLoggedIn): ?>
  <div class="roue-login-prompt">
    <div class="roue-lock-icon">🔐</div>
    <h3>Connexion requise</h3>
    <p>Créez un compte ou connectez-vous pour faire tourner la roue quotidienne !</p>
    <div class="roue-login-btns">
      <a href="<?= $BASE_URL ?>/login.php"    class="roue-btn roue-btn-primary">Se connecter</a>
      <a href="<?= $BASE_URL ?>/register.php" class="roue-btn roue-btn-secondary">Créer un compte</a>
    </div>
  </div>
  <?php endif; ?>

  <div class="wheel-game <?= !$isLoggedIn ? 'wheel-game--locked' : '' ?>">

    <div class="wheel-wrap">
      <div class="wheel-pointer"></div>
      <canvas id="wheelCanvas" width="400" height="400"></canvas>
    </div>

    <div class="wheel-controls">
      <?php if ($alreadyPlayed): ?>
      <div class="roue-played-banner">
        <?php if ($todayGain > 0): ?>
          <div class="played-icon">🏆</div>
          <p>Vous avez gagné <strong><?= $todayGain ?> jeton<?= $todayGain > 1 ? 's' : '' ?></strong> aujourd'hui !</p>
        <?php else: ?>
          <div class="played-icon">😔</div>
          <p>Pas de chance aujourd'hui… Revenez demain !</p>
        <?php endif; ?>
        <p class="roue-next-time">⏳ Prochain tour : <strong><?= $hoursLeft ?>h <?= $minutesLeft ?>min</strong></p>
      </div>
      <?php else: ?>
      <button id="spinBtn" class="roue-spin-btn" <?= !$isLoggedIn ? 'disabled' : '' ?>>
        <span class="spin-btn-icon">🎡</span>
        <span class="spin-btn-text">Faire tourner !</span>
      </button>
      <?php endif; ?>

      <div id="resultPanel" class="result-panel" style="display:none;"></div>
    </div>

  </div>

  <div class="roue-legend">
    <h3>🏆 Gains possibles</h3>
    <div class="legend-grid">
      <?php foreach ($segments as $seg): ?>
      <div class="legend-item">
        <?= $seg['gain'] === 0 ? 'Rien' : '+' . $seg['gain'] . '&nbsp;jeton' . ($seg['gain'] > 1 ? 's' : '') ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <p class="roue-info-text">🗓️ Une seule partie par jour, par utilisateur connecté.</p>

</main>

<script>
const ROUE_CONFIG = {
  isLoggedIn:    <?= $isLoggedIn    ? 'true' : 'false' ?>,
  alreadyPlayed: <?= $alreadyPlayed ? 'true' : 'false' ?>,
  todaySegment:  <?= $todaySegment !== null ? (int)$todaySegment : 'null' ?>,
  todayGain:     <?= $todayGain    !== null ? (int)$todayGain    : 'null' ?>,
};
</script>

<?php include_once(__DIR__ . "/../../includes/footer.php"); ?>
