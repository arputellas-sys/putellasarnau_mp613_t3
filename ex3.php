<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Exercici 3 - Arrays Associatius</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-4">Tornar a l'inici</a>
    <h1 class="mb-4">Arrays Associatius</h1>

    <?php
    // Carrega la funció auxiliar per combinar les claus i els valors.
    require_once __DIR__ . '/includes/functions.php';

    // Dades inicials de demostració.
    $keys_inicial = array("field1"=>"first", "field2"=>"second", "field3"=>"third");
    $values_inicial = array("field1value"=>"dinosaur", "field2value"=>"pig", "field3value"=>"platypus");

    // Recupera l'array actual si ja s'ha enviat des del formulari; sinó, el genera inicialment.
    if (isset($_POST['dades_array']) && !empty($_POST['dades_array'])) {
        $array_combinat = json_decode($_POST['dades_array'], true);
    } else {
        $array_combinat = combinarAmbBucle($keys_inicial, $values_inicial);
    }

    // Gestiona els canvis que es fan des del formulari POST.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Afegeix o actualitza una clau amb el seu valor.
        if (isset($_POST['nova_clau']) && trim($_POST['nova_clau']) !== '' && isset($_POST['nou_valor'])) {
            $clau = trim($_POST['nova_clau']);
            $valor = trim($_POST['nou_valor']);
            $array_combinat[$clau] = $valor;
        }

        // Elimina una clau si existeix.
        if (isset($_POST['eliminar_clau']) && trim($_POST['eliminar_clau']) !== '') {
            $clau_eliminar = trim($_POST['eliminar_clau']);
            if (array_key_exists($clau_eliminar, $array_combinat)) {
                unset($array_combinat[$clau_eliminar]);
            }
        }
    }
    ?>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white"><h5 class="mb-0">Gestió de Parelles</h5></div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="dades_array" value="<?php echo htmlspecialchars(json_encode($array_combinat)); ?>">
                        
                        <h6 class="mb-3">Afegir / Modificar element</h6>
                        <div class="row g-2 mb-4">
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="nova_clau" placeholder="Clau (ex: fourth)">
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="nou_valor" placeholder="Valor (ex: cat)">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success w-100">Desar</button>
                            </div>
                        </div>
                        <hr>
                        <h6 class="mb-3 mt-4">Eliminar element</h6>
                        <div class="row g-2">
                            <div class="col-md-9">
                                <select class="form-select" name="eliminar_clau">
                                    <option value="">Selecciona la clau a eliminar...</option>
                                    <?php foreach($array_combinat as $c => $v): ?>
                                        <option value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-danger w-100">Esborrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white"><h5 class="mb-0">Contingut de l'Array</h5></div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-light"><tr><th class="ps-4">Clau</th><th>Valor</th></tr></thead>
                        <tbody>
                            <?php if(empty($array_combinat)): ?>
                                <tr><td colspan="2" class="text-center py-4">L'array està buit.</td></tr>
                            <?php else: ?>
                                <?php foreach ($array_combinat as $clau => $valor): ?>
                                    <tr><td class="ps-4 fw-bold"><?php echo htmlspecialchars($clau); ?></td><td><?php echo htmlspecialchars($valor); ?></td></tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>