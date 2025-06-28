<?php
// Configuration des jeux de grattage quotidiens

return [

    'cochogratt' => [
        'nom' => 'Cochogratt\' 🐷',
        'gains' => [1, 1, 1, 2, 2, 5, 10], // Pondéré : 1 est plus fréquent
        'limite_par_jour' => 1
    ],

    'ecogratt' => [
        'nom' => 'Écologratt\' 🌿',
        'gains' => [1, 1, 2, 2, 3, 5],
        'limite_par_jour' => 1,
        'couleur' => '#FDE68A',
    'son_gagnant' => 'cochon_win.mp3'
    ],

    'grattvip' => [
        'nom' => 'Gratt\' VIP 👑',
        'gains' => [5, 10, 20, 'BON_D_ACHAT'], // Peut contenir une valeur spéciale
        'limite_par_jour' => 1
    ],

    'smilegratt' => [
        'nom' => 'SmileGratt\' 😁',
        'gains' => [1, 1, 2, 2, 3],
        'limite_par_jour' => 1
    ],

    // Tu pourras en ajouter facilement ici plus tard
];
