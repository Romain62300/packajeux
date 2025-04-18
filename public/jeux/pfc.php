<?php include('../../includes/header.php'); ?>

<main class="container">
  <h2>Pierre Feuille Ciseau</h2>
  <p style="text-align:right"><a href="pfc.php">🔁 Rejouer</a></p>

  <div style="text-align: center; margin: 20px auto;">
    <div id="choix-user" style="margin-bottom: 10px; font-weight: bold;"></div>

    <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 20px;">
      <button onclick="res('Pierre')" class="btn btn-choix">✊ Pierre</button>
      <button onclick="res('Feuille')" class="btn btn-choix">📄 Feuille</button>
      <button onclick="res('Ciseau')" class="btn btn-choix">✂️ Ciseau</button>
    </div>

    <button onclick="testing()" class="btn btn-resultat">🎯 Résultat</button>
  </div>

  <div id="resultat" style="text-align:center; font-size: 18px; margin-top: 20px;"></div>
  <div id="score" style="text-align:center; font-weight:bold; margin-top: 10px;">Gagné: 0 fois. Perdu: 0 fois.</div>
</main>

<script src="../assets/js/pfc.js"></script>

<?php include('../../includes/footer.php'); ?>
