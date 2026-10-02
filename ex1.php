<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Exercici 1 - Capitals Europees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-4">Tornar a l'inici</a>
    <h1 class="mb-4">Capitals Europees</h1>

    <form method="GET" action="" class="mb-4 p-3 bg-light border rounded">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="cerca" class="col-form-label">Cerca per país o capital:</label>
            </div>
            <div class="col-auto">
                <input type="text" id="cerca" name="cerca" class="form-control" 
                       value="<?php echo isset($_GET['cerca']) ? htmlspecialchars($_GET['cerca']) : ''; ?>">
            </div>
            <div class="col-auto">
                <label for="lletra" class="col-form-label">Filtrar per lletra inicial:</label>
            </div>
            <div class="col-auto">
                <input type="text" id="lletra" name="lletra" class="form-control" maxlength="1" style="width: 60px;"
                       value="<?php echo isset($_GET['lletra']) ? htmlspecialchars($_GET['lletra']) : ''; ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Cercar</button>
                <a href="ex1.php" class="btn btn-outline-secondary">Netejar</a>
            </div>
        </div>
    </form>

    <?php
    // Carreguem la funció auxiliar per ordenar les dades.
    require_once __DIR__ . '/includes/functions.php';

    // Llista de països amb la seva capital.
    $ceu = array(
        "Italy"=>"Rome", "Luxembourg"=>"Luxembourg", "Belgium"=> "Brussels",
        "Denmark"=>"Copenhagen", "Finland"=>"Helsinki", "France" => "Paris",
        "Slovakia"=>"Bratislava", "Slovenia"=>"Ljubljana", "Germany" => "Berlin",
        "Greece" => "Athens", "Ireland"=>"Dublin", "Netherlands"=>"Amsterdam",
        "Portugal"=>"Lisbon", "Spain"=>"Madrid", "Sweden"=>"Stockholm",
        "United Kingdom"=>"London", "Cyprus"=>"Nicosia", "Lithuania"=>"Vilnius",
        "Czech Republic"=>"Prague", "Estonia"=>"Tallinn", "Hungary"=>"Budapest",
        "Latvia"=>"Riga", "Malta"=>"Valletta", "Austria" => "Vienna", "Poland"=>"Warsaw"
    );

    // Ordenem les dades per capital i després apliquem filtres si hi ha valor a la cerca.
    $dades_a_mostrar = ordenarPerCapital($ceu);
    $cerca = isset($_GET['cerca']) ? trim($_GET['cerca']) : '';
    $lletra = isset($_GET['lletra']) ? strtoupper(trim($_GET['lletra'])) : '';

    // Filtrar per text i/o per lletra inicial.
    if ($cerca !== '' || $lletra !== '') {
        $dades_filtrades = [];
        foreach ($dades_a_mostrar as $pais => $capital) {
            $passa_filtre = true;
            if ($cerca !== '') {
                // Mostra el país o la capital si coincideixen amb la cerca.
                if (stripos($pais, $cerca) === false && stripos($capital, $cerca) === false) {
                    $passa_filtre = false;
                }
            }
            if ($lletra !== '') {
                // Comprovem la primera lletra del país, en majúscules.
                if (strtoupper(substr($pais, 0, 1)) !== $lletra) {
                    $passa_filtre = false;
                }
            }
            if ($passa_filtre) {
                $dades_filtrades[$pais] = $capital;
            }
        }
        $dades_a_mostrar = $dades_filtrades;
    }
    ?>

    <table class="table table-striped table-hover border">
        <thead class="table-dark">
            <tr><th>País</th><th>Capital</th></tr>
        </thead>
        <tbody>
            <?php
            if (empty($dades_a_mostrar)) {
                echo "<tr><td colspan='2' class='text-center'>No s'han trobat resultats.</td></tr>";
            } else {
                foreach ($dades_a_mostrar as $pais => $capital) {
                    echo "<tr><td>" . htmlspecialchars($pais) . "</td><td>" . htmlspecialchars($capital) . "</td></tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>