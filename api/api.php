<?php

use Controllers\CategoriaVeiculoController;
use Controllers\Rota;

require_once "autoload.php";
require_once __DIR__ . "/configurar.php";
require_once __DIR__ . "/../src/Utils/getParametro.php";

try {   
    $rota = new Rota();
    $endpoint = $rota->getRotaAtual();

    if ($endpoint === "/categorias-veiculo/cadastrar") {
        $rota->post("/categorias-veiculo/cadastrar", CategoriaVeiculoController::class, "cadastrarCategoriaVeiculo");
    }

    if ($endpoint === "/categorias-veiculo") {
        $rota->get("/categorias-veiculo", CategoriaVeiculoController::class, "listarCategoriasVeiculos");
    }

    if ($endpoint === "/categorias-veiculo/editar") {
        $rota->put("/categorias-veiculo/editar", CategoriaVeiculoController::class, "editarCategoriaVeiculo");
    }

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "<br>";
}