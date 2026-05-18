<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config/config.php';

define('JEU_COFFRE', 'coffre');
// Contenu possible de chaque coffre (pondéré)
$PRIZES = [0, 0, 1, 3, 8]; // un seul tiré aléatoirement, placé dans un coffre

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
    $chk->execute([$userId, JEU_COFFRE, $today]);
    if ($chk->fetch()) {
        echo json_encode(['error' => 'already_played', 'message' => 'Vous avez déjà joué aujourd\'hui.']);
        exit;
    }

    $chosen = (int)($_POST['chest'] ?? -1);
    if ($chosen < 0 || $chosen > 4) {
        echo json_encode(['error' => 'invalid', 'message' => 'Coffre invalide.']);
        exit;
    }

    // Place les gains dans les 5 coffres aléatoirement
    shuffle($PRIZES);
    $gain = $PRIZES[$chosen];

    try {
        $pdo->prepare("INSERT INTO jeux_quotidiens_log (utilisateur_id,jeu,date_jeu,gain,segment) VALUES (?,?,?,?,?)")
            ->execute([$userId, JEU_COFFRE, $today, $gain, $chosen]);
        if ($gain > 0) $pdo->prepare("UPDATE utilisateurs SET jetons=jetons+? WHERE id=?")->execute([$gain, $userId]);
        $stJ = $pdo->prepare("SELECT jetons FROM utilisateurs WHERE id=?");
        $stJ->execute([$userId]);
        echo json_encode([
            'success' => true,
            'prizes'  => $PRIZES,
            'chosen'  => $chosen,
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
        $st->execute([$userId, JEU_COFFRE, $today]);
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
<main class="coffre-main">

  <div class="coffre-header">
    <h2 class="coffre-title">🔐 Coffre au Trésor</h2>
    <p class="coffre-subtitle">Choisissez un coffre et découvrez ce qu'il cache !</p>
    <?php if ($isLoggedIn): ?>
    <div class="coffre-badge">💰 Mon solde&nbsp;: <strong id="jetonCount"><?= $userJetons ?></strong> jeton<?= $userJetons !== 1 ? 's' : '' ?></div>
    <?php endif; ?>
  </div>

  <?php if (!$isLoggedIn): ?>
  <div class="coffre-login-prompt">
    <div class="coffre-lock-icon">🔐</div>
    <h3>Connexion requise</h3>
    <p>Connectez-vous pour ouvrir un coffre !</p>
    <div class="coffre-login-btns">
      <a href="<?= $BASE_URL ?>/login.php" class="coffre-btn coffre-btn-primary">Se connecter</a>
      <a href="<?= $BASE_URL ?>/register.php" class="coffre-btn coffre-btn-secondary">Créer un compte</a>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($alreadyPlayed): ?>
  <div class="coffre-played-banner">
    <?php if ($todayGain > 0): ?>
      <div class="coffre-played-icon">💰</div>
      <p>Vous avez trouvé <strong><?= $todayGain ?> jeton<?= $todayGain > 1 ? 's' : '' ?></strong> dans votre coffre !</p>
    <?php else: ?>
      <div class="coffre-played-icon">📭</div>
      <p>Coffre vide aujourd'hui… Réessayez demain !</p>
    <?php endif; ?>
    <p class="coffre-next-time">⏳ Prochain coffre&nbsp;: <strong><?= $hoursLeft ?>h <?= $minutesLeft ?>min</strong></p>
  </div>
  <?php elseif ($isLoggedIn): ?>

  <div class="coffre-game">
    <p class="coffre-pick-label">Choisissez <strong>un coffre</strong> parmi les 5 :</p>
    <div class="coffre-chests" id="coffreChests">
      <?php for ($i = 0; $i < 5; $i++): ?>
      <div class="coffre-chest" data-index="<?= $i ?>">
        <div class="coffre-chest-img">
          <span class="coffre-chest-closed">🔒</span>
          <span class="coffre-chest-open" style="display:none;"></span>
        </div>
        <span class="coffre-chest-label">Coffre <?= $i + 1 ?></span>
      </div>
      <?php endfor; ?>
    </div>

    <div id="coffreResult" class="coffre-result" style="display:none;"></div>
  </div>

  <?php endif; ?>

  <div class="coffre-legend">
    <h3>💎 Gains possibles</h3>
    <p class="coffre-legend-desc">Un coffre contient le grand lot, d'autres ont de petits cadeaux, certains sont vides…</p>
    <div class="coffre-legend-grid">
      <div class="coffre-legend-item">📭 Vide</div>
      <div class="coffre-legend-item">🥉 +1 jeton</div>
      <div class="coffre-legend-item">🥈 +3 jetons</div>
      <div class="coffre-legend-item">🥇 +8 jetons</div>
    </div>
  </div>

  <p class="coffre-info">🗓️ Un seul coffre par jour, par utilisateur connecté.</p>

</main>

<script>
const COFFRE_CONFIG = {
  isLoggedIn:    <?= $isLoggedIn    ? 'true' : 'false' ?>,
  alreadyPlayed: <?= $alreadyPlayed ? 'true' : 'false' ?>,
};
</script>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
