<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Exercici 2 - Temperatures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-4">Tornar a l'inici</a>
    <h1 class="mb-4">Anàlisi de Temperatures</h1>

    <?php
    // Carreguem les funcions helpers reutilitzables.
    require_once __DIR__ . '/includes/functions.php';

    // Llista base de temperatures inicials de l'exercici.
    $temperatures_inicials = [78, 60, 62, 68, 71, 68, 73, 85, 66, 64, 76, 63, 75, 76, 73, 68, 62, 73, 72, 65, 74, 62, 62, 65, 64, 68, 73, 75, 79, 73];

    // Si la pàgina ve de formulari, reutilitzem la llista enviada; si no, usem la inicial.
    if (isset($_POST['llista_temp']) && !empty($_POST['llista_temp'])) {
        $temperatures = explode(',', $_POST['llista_temp']);
    } else {
        $temperatures = $temperatures_inicials;
    }

    // Afegim una nova temperatura si s'ha enviat i és numèrica.
    if (isset($_POST['nova_temp']) && is_numeric($_POST['nova_temp'])) {
        $temperatures[] = (float)$_POST['nova_temp'];
    }

    // Escala activa: Fahrenheit per defecte o Celsius si l'usuari la canvia.
    $escala = isset($_POST['escala']) ? $_POST['escala'] : 'F';

    // Convertim les temperatures segons l'escala seleccionada per a mostrar i calcular estadístiques.
    $temperatures_a_mostrar = [];
    foreach ($temperatures as $t) {
        if ($escala === 'C') {
            $temperatures_a_mostrar[] = convertirFtoC($t);
        } else {
            $temperatures_a_mostrar[] = round($t, 2);
        }
    }

    // Càlcul de les estadístiques bàsiques i dels top 5.
    $mitjana = calcularMitjana($temperatures_a_mostrar);
    $mediana = calcularMediana($temperatures_a_mostrar);
    $maxima = max($temperatures_a_mostrar);
    $minima = min($temperatures_a_mostrar);
    $top_altes = obtenirTop5Altes($temperatures_a_mostrar);
    $top_baixes = obtenirTop5Baixes($temperatures_a_mostrar);
    $simbol = ($escala === 'C') ? '°C' : '°F';
    ?>

    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="">
                <!-- Es guarda la llista actual per conservar les dades en cada enviament del formulari. -->
                <input type="hidden" name="llista_temp" value="<?php echo htmlspecialchars(implode(',', $temperatures)); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="nova_temp" class="form-label">Afegir nova temperatura (<?php echo $simbol; ?>):</label>
                        <input type="number" step="0.01" class="form-control" id="nova_temp" name="nova_temp" placeholder="Ex: 80">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Afegir</button>
                    </div>
                    <div class="col-md-6 text-end">
                        <label class="form-label me-2">Escala de temperatura:</label>
                        <div class="btn-group" role="group">
                            <button type="submit" name="escala" value="F" class="btn <?php echo ($escala === 'F') ? 'btn-primary' : 'btn-outline-primary'; ?>">Fahrenheit</button>
                            <button type="submit" name="escala" value="C" class="btn <?php echo ($escala === 'C') ? 'btn-primary' : 'btn-outline-primary'; ?>">Celsius</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Resum estadístic de la llista actual. -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light"><h5 class="mb-0">Estadístiques Bàsiques</h5></div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><td>Mitjana:</td><td class="text-end fw-bold"><?php echo htmlspecialchars(number_format($mitjana, 2)) . $simbol; ?></td></tr>
                        <tr><td>Mediana:</td><td class="text-end fw-bold"><?php echo htmlspecialchars(number_format($mediana, 2)) . $simbol; ?></td></tr>
                        <tr><td>Temperatura màxima:</td><td class="text-end fw-bold"><?php echo htmlspecialchars(number_format($maxima, 2)) . $simbol; ?></td></tr>
                        <tr><td>Temperatura mínima:</td><td class="text-end fw-bold"><?php echo htmlspecialchars(number_format($minima, 2)) . $simbol; ?></td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Llistat de les temperatures més altes i més baixes. -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light"><h5 class="mb-0">Top 5 Temperatures</h5></div>
                <div class="card-body row">
                    <div class="col-6">
                        <h6>Més altes:</h6>
                        <ul class="list-unstyled">
                            <?php foreach($top_altes as $t) echo "<li>" . htmlspecialchars(number_format($t, 2)) . $simbol . "</li>"; ?>
                        </ul>
                    </div>
                    <div class="col-6">
                        <h6>Més baixes:</h6>
                        <ul class="list-unstyled">
                            <?php foreach($top_baixes as $t) echo "<li>" . htmlspecialchars(number_format($t, 2)) . $simbol . "</li>"; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visualització completa de la llista amb l'índex i la temperatura final. -->
    <div class="card">
        <div class="card-header bg-light"><h5 class="mb-0">Totes les temperatures</h5></div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead><tr><th class="ps-4">#</th><th>Temperatura (<?php echo $simbol; ?>)</th></tr></thead>
                <tbody>
                    <?php 
                    foreach ($temperatures_a_mostrar as $index => $temp) {
                        echo "<tr><td class='ps-4'>" . htmlspecialchars($index + 1) . "</td><td>" . htmlspecialchars(number_format($temp, 2)) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>