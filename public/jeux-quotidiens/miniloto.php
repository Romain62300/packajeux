<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config/config.php';

define('JEU_LOTO', 'miniloto');
define('LOTO_PICK', 5);
define('LOTO_MAX',  20);

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
    $chk->execute([$userId, JEU_LOTO, $today]);
    if ($chk->fetch()) {
        echo json_encode(['error' => 'already_played', 'message' => 'Vous avez déjà joué aujourd\'hui.']);
        exit;
    }

    // Valide les numéros du joueur
    $raw    = $_POST['numbers'] ?? '';
    $parsed = array_map('intval', explode(',', $raw));
    $chosen = array_unique(array_filter($parsed, function($n) { return $n >= 1 && $n <= LOTO_MAX; }));
    if (count($chosen) !== LOTO_PICK) {
        echo json_encode(['error' => 'invalid', 'message' => 'Choisissez exactement ' . LOTO_PICK . ' numéros.']);
        exit;
    }

    // Tirage système
    $pool = range(1, LOTO_MAX);
    shuffle($pool);
    $drawn = array_slice($pool, 0, LOTO_PICK);
    sort($drawn);

    $matches = count(array_intersect($chosen, $drawn));
    if ($matches >= 5)     $gain = 20;
    elseif ($matches >= 4) $gain = 7;
    elseif ($matches >= 3) $gain = 3;
    elseif ($matches >= 2) $gain = 1;
    else                   $gain = 0;

    try {
        $pdo->prepare("INSERT INTO jeux_quotidiens_log (utilisateur_id,jeu,date_jeu,gain) VALUES (?,?,?,?)")
            ->execute([$userId, JEU_LOTO, $today, $gain]);
        if ($gain > 0) $pdo->prepare("UPDATE utilisateurs SET jetons=jetons+? WHERE id=?")->execute([$gain, $userId]);
        $stJ = $pdo->prepare("SELECT jetons FROM utilisateurs WHERE id=?");
        $stJ->execute([$userId]);
        echo json_encode([
            'success' => true,
            'drawn'   => $drawn,
            'matches' => $matches,
            'gain'    => $gain,
            'jetons'  => (int)$stJ->fetchColumn(),
        ]);
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
        $st->execute([$userId, JEU_LOTO, $today]);
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
<main class="loto-main">

  <div class="loto-header">
    <h2 class="loto-title">🎟️ Mini Loterie</h2>
    <p class="loto-subtitle">Choisissez <?= LOTO_PICK ?> numéros entre 1 et <?= LOTO_MAX ?> et tentez votre chance !</p>
    <?php if ($isLoggedIn): ?>
    <div class="loto-badge">💰 Mon solde&nbsp;: <strong id="jetonCount"><?= $userJetons ?></strong> jeton<?= $userJetons !== 1 ? 's' : '' ?></div>
    <?php endif; ?>
  </div>

  <?php if (!$isLoggedIn): ?>
  <div class="loto-login-prompt">
    <div class="loto-lock">🔐</div>
    <h3>Connexion requise</h3>
    <p>Connectez-vous pour jouer à la Mini Loterie !</p>
    <div class="loto-login-btns">
      <a href="<?= $BASE_URL ?>/login.php" class="loto-btn loto-btn-primary">Se connecter</a>
      <a href="<?= $BASE_URL ?>/register.php" class="loto-btn loto-btn-secondary">Créer un compte</a>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($alreadyPlayed): ?>
  <div class="loto-played-banner">
    <?php if ($todayGain > 0): ?>
      <div class="loto-played-icon">🎉</div>
      <p>Bravo ! Vous avez gagné <strong><?= $todayGain ?> jeton<?= $todayGain > 1 ? 's' : '' ?></strong> !</p>
    <?php else: ?>
      <div class="loto-played-icon">🎟️</div>
      <p>Pas de chance aujourd'hui… La chance tournera demain !</p>
    <?php endif; ?>
    <p class="loto-next-time">⏳ Prochain tirage&nbsp;: <strong><?= $hoursLeft ?>h <?= $minutesLeft ?>min</strong></p>
  </div>
  <?php elseif ($isLoggedIn): ?>

  <div class="loto-game">
    <p class="loto-pick-label">Sélectionnez <strong><?= LOTO_PICK ?> numéros</strong> :</p>
    <div class="loto-grid" id="lotoGrid">
      <?php for ($n = 1; $n <= LOTO_MAX; $n++): ?>
      <button class="loto-num" data-n="<?= $n ?>"><?= $n ?></button>
      <?php endfor; ?>
    </div>

    <div class="loto-selection">
      Sélection&nbsp;: <span id="lotoSelected">—</span>
    </div>

    <button id="lotoPlayBtn" class="loto-play-btn" disabled>
      <span class="loto-btn-icon">🎟️</span>
      <span>Valider ma grille !</span>
    </button>

    <div id="lotoResult" class="loto-result" style="display:none;"></div>
  </div>

  <?php endif; ?>

  <div class="loto-legend">
    <h3>🏆 Tableau des gains</h3>
    <div class="loto-legend-grid">
      <div class="loto-legend-item"><span>2 bons numéros</span><strong>+1 jeton</strong></div>
      <div class="loto-legend-item"><span>3 bons numéros</span><strong>+3 jetons</strong></div>
      <div class="loto-legend-item"><span>4 bons numéros</span><strong>+7 jetons</strong></div>
      <div class="loto-legend-item loto-jackpot-item"><span>5 bons numéros</span><strong>+20 jetons 🎉</strong></div>
    </div>
  </div>

  <p class="loto-info">🗓️ Une seule grille par jour, par utilisateur connecté.</p>

</main>

<script>
const LOTO_CONFIG = {
  isLoggedIn:    <?= $isLoggedIn    ? 'true' : 'false' ?>,
  alreadyPlayed: <?= $alreadyPlayed ? 'true' : 'false' ?>,
  pick:          <?= LOTO_PICK ?>,
  max:           <?= LOTO_MAX ?>,
};
</script>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
