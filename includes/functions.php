<?php
// --- EXERCICI 1 ---
function ordenarPerCapital($array) {
    asort($array); // endreço per capital
    return $array;
}

// --- EXERCICI 2 ---
function calcularMitjana($arr) {
    return array_sum($arr) / count($arr); // sumo tots els valors i divideixo pel nombre d'elements
}

function calcularMediana($arr) {
    sort($arr);
    $count = count($arr);
    $meitat = floor(($count - 1) / 2);
    // miro si és parell o senar per treure la mediana
    if ($count % 2) {
        return $arr[$meitat];
    } else {
        return ($arr[$meitat] + $arr[$meitat + 1]) / 2.0;
    }
}

function convertirFtoC($f) {
    return round(($f - 32) * 5 / 9, 2); // passem a celsius amb 2 decimals
}

function obtenirTop5Altes($arr) {
    rsort($arr);
    return array_slice($arr, 0, 5); // agafo les 5 primeres
}

function obtenirTop5Baixes($arr) {
    sort($arr);
    return array_slice($arr, 0, 5);
}

// --- EXERCICI 3 ---
function combinarAmbBucle($keys, $values) {
    $resultat = [];
    $valors_clau = array_values($keys);
    $valors_valor = array_values($values);
    
    $limit = min(count($valors_clau), count($valors_valor));
    for ($i = 0; $i < $limit; $i++) { // bucle fins al mínim dels dos arrays
        $resultat[$valors_clau[$i]] = $valors_valor[$i];
    }
    return $resultat;
}

function combinarAmbFuncio($keys, $values) { // utilitzem array_combine per combinar els arrays
    return array_combine(array_values($keys), array_values($values));
}

// --- EXERCICI 4 ---
function comptarTotalAlumnes($matriu) {
    $total = 0;
    foreach ($matriu as $nivell => $idiomes) {
        foreach ($idiomes as $idioma => $alumnes) { // recorrem cada nivell i idioma
            $total += count($alumnes);
        }
    }
    return $total;
}

function comptarTotalPerIdioma($matriu, $idioma_buscat) {
    $total = 0;
    foreach ($matriu as $nivell => $idiomes) { // recorrem cada nivell
        if (isset($idiomes[$idioma_buscat])) {
            $total += count($idiomes[$idioma_buscat]); // sumem el nombre d'alumnes per l'idioma buscat
        }
    }
    return $total;
}

function buscarGrupMesNombros($matriu) {
    $max = -1;
    $grup = "";
    foreach ($matriu as $nivell => $idiomes) {
        foreach ($idiomes as $idioma => $alumnes) {
            if (count($alumnes) > $max) { // si el nombre d'alumnes és més gran que el màxim actual, actualitzem
                $max = count($alumnes);
                $grup = "$nivell - $idioma ($max alumnes)";
            }
        }
    }
    return $grup;
}

function buscarGrupMenysNombros($matriu) {
    $min = 999999;
    $grup = "";
    foreach ($matriu as $nivell => $idiomes) { // recorrem cada nivell
        foreach ($idiomes as $idioma => $alumnes) {
            if (count($alumnes) < $min) {
                $min = count($alumnes);
                $grup = "$nivell - $idioma ($min alumnes)";
            }
        }
    }
    return $grup;
}

function calcularMitjanaPerNivell($matriu, $nivell_buscat) {
    if (!isset($matriu[$nivell_buscat])) return 0; // si el nivell no existeix, retornem 0
    $total_alumnes = 0;
    $num_idiomes = count($matriu[$nivell_buscat]);
    foreach ($matriu[$nivell_buscat] as $idioma => $alumnes) {
        $total_alumnes += count($alumnes);
    }
    return $num_idiomes > 0 ? round($total_alumnes / $num_idiomes, 2) : 0;
}

function calcularMitjanaPerIdioma($matriu, $idioma_buscat) {
    $total_alumnes = comptarTotalPerIdioma($matriu, $idioma_buscat);
    $num_nivells = count($matriu);
    return $num_nivells > 0 ? round($total_alumnes / $num_nivells, 2) : 0; // si hi ha nivells, calculem la mitjana
}
?>