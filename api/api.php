<?php

use Controllers\CategoriaVeiculoController;
use Controllers\LoginController;
use Controllers\ProprietarioController;
use Controllers\Rota;
use Controllers\UsuarioController;
use Controllers\VeiculoController;
use Controllers\VistoriaVeicularController;
use Utils\Resposta;

require_once "autoload.php";
require_once __DIR__ . "/configurar.php";
require_once __DIR__ . "/../src/Utils/getParametro.php";

try {   
    $rota = new Rota();
    $endpoint = $rota->getRotaAtual();

    // realizar login
    if ($endpoint === "/login") {
        $rota->post("/login", LoginController::class, "login");
    }

    // efetuar logout
    if ($endpoint === "/logout") {
        $rota->post("/logout", LoginController::class, "logout");
    }

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

    // buscar veiculo pelo id
    if ($endpoint === "/veiculos/buscar-pelo-id") {
        $rota->get("/veiculos/buscar-pelo-id", VeiculoController::class, "buscarVeiculoPeloId");
    }

    // realizar vistoria veicular
    if ($endpoint === "/vistorias/veicular/cadastrar") {
        $rota->post("/vistorias/veicular/cadastrar", VistoriaVeicularController::class, "cadastrarVistoriaVeicular");
    }

    // buscar vistoria veicular pelo id
    if ($endpoint === "/vistorias/veicular/buscar-pelo-id") {
        $rota->get("/vistorias/veicular/buscar-pelo-id", VistoriaVeicularController::class, "buscarVistoriaVeicularPeloId");
    }

    // cadastrar usuário
    if ($endpoint === "/usuarios/cadastrar") {
        $rota->post("/usuarios/cadastrar", UsuarioController::class, "cadastrarUsuario");
    }

    // buscar todas as vistorias veiculares
    if ($endpoint === "/vistorias/veicular") {
        $rota->get("/vistorias/veicular", VistoriaVeicularController::class, "buscarVistoriasVeiculares");
    }

    // deletar vistoria veicular
    if ($endpoint === "/vistorias/veicular/deletar") {
        $rota->delete("/vistorias/veicular/deletar", VistoriaVeicularController::class, "deletarVistoriaVeicular");
    }

    Resposta::response(false, "404 - Rota inválida.");
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "<br>";
}