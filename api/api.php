<?php

use Controllers\CategoriaVeiculoController;
use Controllers\ProprietarioController;
use Controllers\Rota;
use Controllers\VeiculoController;
use Utils\Resposta;

require_once "autoload.php";
require_once __DIR__ . "/configurar.php";
require_once __DIR__ . "/../src/Utils/getParametro.php";

try {   
    $rota = new Rota();
    $endpoint = $rota->getRotaAtual();

    // cadastrar categoria de veiculo
    if ($endpoint === "/categorias-veiculo/cadastrar") {
        $rota->post("/categorias-veiculo/cadastrar", CategoriaVeiculoController::class, "cadastrarCategoriaVeiculo");
    }

    // buscar todas as categorias de veiculo
    if ($endpoint === "/categorias-veiculo") {
        $rota->get("/categorias-veiculo", CategoriaVeiculoController::class, "listarCategoriasVeiculos");
    }

    // editar categoria de veiculo
    if ($endpoint === "/categorias-veiculo/editar") {
        $rota->put("/categorias-veiculo/editar", CategoriaVeiculoController::class, "editarCategoriaVeiculo");
    }

    // buscar categoria de veiculo pelo id
    if ($endpoint === "/categorias-veiculo/buscar-pelo-id") {
        $rota->get("/categorias-veiculo/buscar-pelo-id", CategoriaVeiculoController::class, "buscarCategoriaVeiculoPeloId");
    }

    // cadastrar proprietário
    if ($endpoint === "/proprietarios/cadastrar") {
        $rota->post("/proprietarios/cadastrar", ProprietarioController::class, "cadastrarProprietario");
    }

    // buscar proprietários
    if ($endpoint === "/proprietarios") {
        $rota->get("/proprietarios", ProprietarioController::class, "buscarProprietarios");
    }

    // buscar proprietário pelo cpf
    if ($endpoint === "/proprietarios/buscar-pelo-cpf") {
        $rota->get("/proprietarios/buscar-pelo-cpf", ProprietarioController::class, "buscarProprietarioPeloCpf");
    }

    // buscar proprietário pelo id
    if ($endpoint === "/proprietarios/buscar-pelo-id") {
        $rota->get("/proprietarios/buscar-pelo-id", ProprietarioController::class, "buscarProprietarioPeloId");
    }

    // cadastrar veiculo na base de dados
    if ($endpoint === "/veiculos/cadastrar") {
        $rota->post("/veiculos/cadastrar", VeiculoController::class, "cadastrarVeiculo");
    }

    // buscar veiculos de forma paginada
    if ($endpoint === "/veiculos") {
        $rota->get("/veiculos", VeiculoController::class, "buscarVeiculos");
    }

    Resposta::response(false, "404 - Rota inválida.");
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "<br>";
}