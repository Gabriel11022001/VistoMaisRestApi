<?php

use Controllers\Rota;

require_once "autoload.php";
require_once __DIR__ . "/configurar.php";
require_once __DIR__ . "/../src/Utils/getParametro.php";

try {   
    $rota = new Rota();

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "<br>";
}