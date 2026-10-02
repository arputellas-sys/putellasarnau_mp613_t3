<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Exercici 4 - Acadèmia d'Idiomes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 mb-5">
    <a href="index.php" class="btn btn-secondary mb-4">Tornar a l'inici</a>
    <h1 class="mb-4">Gestió d'Acadèmia d'Idiomes</h1>

    <?php
    require_once __DIR__ . '/includes/functions.php';

    // Dades inicials de l'acadèmia: nivell -> idioma -> alumnes.
    $matriu_inicial = [
        "Bàsic" => [
            "Anglès" => ["Marc", "Laura"], "Francès" => ["Pol"],
            "Alemany" => [], "Rus" => ["Ivan"]
        ],
        "Mitjà" => [
            "Anglès" => ["Anna", "Jordi"], "Francès" => ["Mireia"],
            "Alemany" => ["Klaus"], "Rus" => []
        ],
        "Perfeccionament" => [
            "Anglès" => ["Joan"], "Francès" => [],
            "Alemany" => ["Marta"], "Rus" => ["Igor"]
        ]
    ];

    if (isset($_POST['dades_academia']) && !empty($_POST['dades_academia'])) {
        $academia = json_decode($_POST['dades_academia'], true);
    } elseif (isset($_GET['dades_academia']) && !empty($_GET['dades_academia'])) {
        $academia = json_decode($_GET['dades_academia'], true);
    } else {
        $academia = $matriu_inicial;
    }

    $missatge = "";
// Accions del formulari: afegir, eliminar o moure un alumne.
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accio'])) {
        $accio = $_POST['accio'];

        if ($accio === 'afegir') {
            $nom = trim($_POST['nom']);
            $nivell = $_POST['nivell'];
            $idioma = $_POST['idioma'];
            if ($nom !== '') {
                $academia[$nivell][$idioma][] = $nom;
                $missatge = "<div class='alert alert-success'>$nom afegit a $nivell - $idioma.</div>";
            }
        }
        elseif ($accio === 'eliminar') {
            $nom = trim($_POST['nom']);
            $nivell = $_POST['nivell'];
            $idioma = $_POST['idioma'];
            
            $index = array_search($nom, $academia[$nivell][$idioma]);
            if ($index !== false) {
                unset($academia[$nivell][$idioma][$index]);
                $academia[$nivell][$idioma] = array_values($academia[$nivell][$idioma]); 
                $missatge = "<div class='alert alert-success'>$nom eliminat de $nivell - $idioma.</div>";
            } else {
                $missatge = "<div class='alert alert-danger'>No s'ha trobat a $nom en aquest grup.</div>";
            }
        }
        elseif ($accio === 'moure') {
            $nom = trim($_POST['nom']);
            $n_orig = $_POST['nivell_origen'];
            $i_orig = $_POST['idioma_origen'];
            $n_dest = $_POST['nivell_desti'];
            $i_dest = $_POST['idioma_desti'];

            $index = array_search($nom, $academia[$n_orig][$i_orig]);
            if ($index !== false) {
                unset($academia[$n_orig][$i_orig][$index]);
                $academia[$n_orig][$i_orig] = array_values($academia[$n_orig][$i_orig]);
                $academia[$n_dest][$i_dest][] = $nom;
                $missatge = "<div class='alert alert-success'>$nom mogut a $n_dest - $i_dest.</div>";
            } else {
                $missatge = "<div class='alert alert-danger'>L'alumne no estava al grup d'origen.</div>";
            }
        }
    // Cerca un alumne dins de tota la matriu i mostra en quins grups està.
    }

    $resultat_cerca = "";
    if (isset($_GET['cerca_nom']) && trim($_GET['cerca_nom']) !== '') {
        $nom_buscat = trim($_GET['cerca_nom']);
        $trobat_a = [];
        
        foreach ($academia as $nivell => $idiomes) {
            foreach ($idiomes as $idioma => $alumnes) {
                $alumnes_min = array_map('strtolower', $alumnes);
                if (in_array(strtolower($nom_buscat), $alumnes_min)) {
                    $trobat_a[] = "$nivell - $idioma";
                }
            }
        }
        
        if (count($trobat_a) > 0) {
            $resultat_cerca = "<div class='alert alert-info'>L'alumne <strong>" . htmlspecialchars($nom_buscat) . "</strong> està inscrit a: " . implode(", ", $trobat_a) . "</div>";
        } else {
            $resultat_cerca = "<div class='alert alert-warning'>No s'ha trobat l'alumne.</div>";
    // Preparació de l'estat actual per tornar-lo a enviar i definir l'ordre de visualització.
        }
    }
    
    $json_estat = htmlspecialchars(json_encode($academia));
    $nivells = ["Bàsic", "Mitjà", "Perfeccionament"];
    $idiomes_llista = ["Anglès", "Francès", "Alemany", "Rus"];
    ?>

    <?php echo $missatge; ?>
    <?php echo $resultat_cerca; ?>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Matriu d'Alumnes</h5>
                    <span!-- Taula amb els nivells a les files i els idiomes a les columnes. -->
                        < class="badge bg-primary">Total: <?php echo comptarTotalAlumnes($academia); ?></span>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-bordered table-striped mb-0 text-center">
                        <thead class="table-light">
                            <tr><th>Nivell \ Idioma</th><?php foreach($idiomes_llista as $idm) echo "<th>$idm</th>"; ?></tr>
                        </thead>
                        <tbody>
                            <?php foreach($nivells as $niv): ?>
                            <tr>
                                <th class="align-middle text-start ps-3"><?php echo $niv; ?></th>
                                <?php foreach($idiomes_llista as $idm): ?>
                                    <td>
                                        <?php 
                                        $alumnes = $academia[$niv][$idm];
                                        if (count($alumnes) === 0) {
                                            echo "<span class='text-muted small'>- Buit -</span>";
                                        } else {
                                            echo "<span class='badge bg-secondary mb-1'>" . count($alumnes) . "</span><br>";
                                            echo "<small>" . implode("<br>", array_map('htmlspecialchars', $alumnes)) . "</small>";
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light small">
                    <strong>Més nombrós:</strong> <?php echo buscarGrupMesNombros($academia); ?> | 
                    <strong>Menys nombrós:</strong> <?php echo buscarGrupMenysNombros($academia); ?>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white"><h5 class="mb-0">Cercar Alumne</h5></div>
                <div class="card-body">
                    <form method="GET" action="">
                        <input type="hidden" name="dades_academia" value="<?php echo $json_estat; ?>">
                        <div class="input-group">
                            <input type="text" name="cerca_nom" class="form-control" placeholder="Nom..." required>
                            <button type="submit" class="btn btn-outline-secondary">Cercar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white"><h5 class="mb-0">Accions</h5></div>
                <div class="card-body">
                    <form method="POST" action="" class="mb-4">
                        <input type="hidden" name="dades_academia" value="<?php echo $json_estat; ?>">
                        <div class="row g-2 mb-2">
                            <div class="col-12"><input type="text" name="nom" class="form-control" placeholder="Nom complet" required></div>
                            <div class="col-6">
                                <select name="nivell" class="form-select"><?php foreach($nivells as $n) echo "<option value='$n'>$n</option>"; ?></select>
                            </div>
                            <div class="col-6">
                                <select name="idioma" class="form-select"><?php foreach($idiomes_llista as $i) echo "<option value='$i'>$i</option>"; ?></select>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" name="accio" value="afegir" class="btn btn-success w-50">Afegir</button>
                            <button type="submit" name="accio" value="eliminar" class="btn btn-danger w-50">Eliminar</button>
                        </div>
                    </form>
                    <hr>
                    <form method="POST" action="">
                        <input type="hidden" name="dades_academia" value="<?php echo $json_estat; ?>">
                        <input type="hidden" name="accio" value="moure">
                        <h6 class="mb-2">Moure de grup</h6>
                        <div class="row g-2 mb-2">
                            <div class="col-12"><input type="text" name="nom" class="form-control" placeholder="Nom a moure" required></div>
                        </div>
                        <div class="row g-2 mb-2 text-center align-items-center">
                            <div class="col-5">
                                <small class="text-muted d-block">Origen</small>
                                <select name="nivell_origen" class="form-select form-select-sm mb-1"><?php foreach($nivells as $n) echo "<option value='$n'>$n</option>"; ?></select>
                                <select name="idioma_origen" class="form-select form-select-sm"><?php foreach($idiomes_llista as $i) echo "<option value='$i'>$i</option>"; ?></select>
                            </div>
                            <div class="col-2 fw-bold text-primary">-></div>
                            <div class="col-5">
                                <small class="text-muted d-block">Destí</small>
                                <select name="nivell_desti" class="form-select form-select-sm mb-1"><?php foreach($nivells as $n) echo "<option value='$n'>$n</option>"; ?></select>
                                <select name="idioma_desti" class="form-select form-select-sm"><?php foreach($idiomes_llista as $i) echo "<option value='$i'>$i</option>"; ?></select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 mt-2">Moure Alumne</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>