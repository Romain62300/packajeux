<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config/config.php';

define('JEU_VIP', 'vipgratt');
$SYMBOLS = ['💎','👑','⭐','💰','🏆','🎩'];
$GAINS   = ['💎' => 20, '👑' => 15, '⭐' => 10, '💰' => 8, '🏆' => 5, '🎩' => 3];

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
    $chk->execute([$userId, JEU_VIP, $today]);
    if ($chk->fetch()) {
        echo json_encode(['error' => 'already_played', 'message' => 'Vous avez déjà joué aujourd\'hui.']);
        exit;
    }

    // 3 zones VIP — probabilité 15% de triple match (jackpot)
    $jackpot = random_int(1, 100) <= 15;
    if ($jackpot) {
        $sym  = $SYMBOLS[random_int(0, 5)];
        $grid = [$sym, $sym, $sym];
        $gain = $GAINS[$sym];
    } else {
        do {
            $grid = [];
            for ($i = 0; $i < 3; $i++) $grid[] = $SYMBOLS[random_int(0, 5)];
        } while (count(array_unique($grid)) === 1); // garantit pas de triple
        $gain = 0;
    }

    try {
        $pdo->prepare("INSERT INTO jeux_quotidiens_log (utilisateur_id,jeu,date_jeu,gain) VALUES (?,?,?,?)")
            ->execute([$userId, JEU_VIP, $today, $gain]);
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
        $st->execute([$userId, JEU_VIP, $today]);
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
<main class="vip-main">

  <div class="vip-header">
    <div class="vip-crown-deco">👑</div>
    <h2 class="vip-title">Grattage VIP</h2>
    <p class="vip-subtitle">Alignez 3 symboles identiques pour remporter le grand lot !</p>
    <?php if ($isLoggedIn): ?>
    <div class="vip-badge">💰 Mon solde&nbsp;: <strong id="jetonCount"><?= $userJetons ?></strong> jeton<?= $userJetons !== 1 ? 's' : '' ?></div>
    <?php endif; ?>
  </div>

  <?php if (!$isLoggedIn): ?>
  <div class="vip-login-prompt">
    <div class="vip-lock">🔐</div>
    <h3>Accès VIP requis</h3>
    <p>Connectez-vous pour accéder au grattage premium !</p>
    <div class="vip-login-btns">
      <a href="<?= $BASE_URL ?>/login.php" class="vip-btn vip-btn-primary">Se connecter</a>
      <a href="<?= $BASE_URL ?>/register.php" class="vip-btn vip-btn-secondary">Créer un compte</a>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($alreadyPlayed): ?>
  <div class="vip-played-banner">
    <?php if ($todayGain > 0): ?>
      <div class="vip-played-icon">🏆</div>
      <p>Jackpot ! Vous avez remporté <strong><?= $todayGain ?> jeton<?= $todayGain > 1 ? 's' : '' ?></strong> !</p>
    <?php else: ?>
      <div class="vip-played-icon">💎</div>
      <p>Pas d'alignement aujourd'hui… Réessayez demain !</p>
    <?php endif; ?>
    <p class="vip-next-time">⏳ Prochain grattage&nbsp;: <strong><?= $hoursLeft ?>h <?= $minutesLeft ?>min</strong></p>
  </div>
  <?php elseif ($isLoggedIn): ?>

  <div class="vip-game">
    <div class="vip-ticket">
      <div class="vip-ticket-header">
        <span>✦</span> TICKET VIP <span>✦</span>
      </div>
      <div class="vip-zones" id="vipZones">
        <?php for ($i = 0; $i < 3; $i++): ?>
        <div class="vip-zone" data-index="<?= $i ?>">
          <div class="vip-zone-cover">
            <span class="vip-zone-hint">Grattez</span>
          </div>
          <div class="vip-zone-symbol"></div>
        </div>
        <?php endfor; ?>
      </div>
      <div class="vip-ticket-footer">✦ Alignez 3 symboles identiques ✦</div>
    </div>

    <button id="vipPlayBtn" class="vip-play-btn">
      <span class="vip-btn-icon">👑</span>
      <span>Gratter le ticket !</span>
    </button>

    <div id="vipResult" class="vip-result" style="display:none;"></div>
  </div>

  <?php endif; ?>

  <div class="vip-legend">
    <h3>💎 Gains possibles (3 symboles identiques)</h3>
    <div class="vip-legend-grid">
      <?php foreach ($GAINS as $sym => $g): ?>
      <div class="vip-legend-item"><span><?= $sym ?></span><strong>+<?= $g ?> jeton<?= $g > 1 ? 's' : '' ?></strong></div>
      <?php endforeach; ?>
    </div>
  </div>

  <p class="vip-info">🗓️ Une seule partie par jour, par utilisateur connecté.</p>

</main>

<script>
const VIP_CONFIG = {
  isLoggedIn:    <?= $isLoggedIn    ? 'true' : 'false' ?>,
  alreadyPlayed: <?= $alreadyPlayed ? 'true' : 'false' ?>,
};
</script>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
