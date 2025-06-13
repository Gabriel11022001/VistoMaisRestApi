<?php

namespace Servico;

use Exception;
use LDAP\Result;
use Models\Veiculo;
use Repositorio\CategoriaVeiculoRepositorio;
use Repositorio\ProprietarioRepositorio;
use Repositorio\VeiculoRepositorio;
use Utils\Resposta;
use Validators\ValidaCamposCadastroVeiculo;

class VeiculoServico extends ServicoBase {

    private $veiculoRepositorio;
    private $propritarioRepositorio;
    private $categoriaVeiculoRepositorio;

    public function __construct()
    {
        parent::__construct();

        $this->veiculoRepositorio = new VeiculoRepositorio($this->bancoDados);
        $this->propritarioRepositorio = new ProprietarioRepositorio($this->bancoDados);
        $this->categoriaVeiculoRepositorio = new CategoriaVeiculoRepositorio($this->bancoDados);
    }

    // cadastrar veiculo
    public function cadastrarVeiculo() {
        
        try {
            $marca = getParametro("marca");
            $modelo = getParametro("modelo");
            $anoFabricacao = getParametro("ano_fabricacao");
            $anoModelo = getParametro("ano_modelo");
            $cor = getParametro("cor");
            $placa = getParametro("placa");
            $renavam = getParametro("renavam");
            $tipoCombustivel = getParametro("tipo_combustivel");
            $categoriaVeiculoId = getParametro("categoria_veiculo_id");
            $proprietarioId = getParametro("proprietario_id");
            $numeroChassi = getParametro("numero_chassi");

            $errosCampos = ValidaCamposCadastroVeiculo::validar(array(
                "marca" => $marca,
                "modelo" => $modelo,
                "ano_lancamento" => $anoFabricacao,
                "ano_modelo" => $anoModelo,
                "cor" => $cor,
                "placa" => $placa,
                "renavam" => $renavam,
                "numero_chassi" => $numeroChassi,
                "tipo_combustivel" => $tipoCombustivel,
                "categoria_veiculo_id" => $categoriaVeiculoId,
                "proprietario_id" => $proprietarioId
            ));

            if (!empty($errosCampos)) {
                Resposta::response(false, "Erros nos campos durante o cadastro.", $errosCampos);
            }

            // validar e esse proprietário existe
            $propritarioVeiculo = $this->propritarioRepositorio->buscarPeloId($proprietarioId);

            if (empty($propritarioVeiculo)) {
                Resposta::response(false, "Proprietário não encontrado.");
            }

            // validar se a categoria do veiculo existe
            $categoriaVeiculo = $this->categoriaVeiculoRepositorio->buscarPeloId($categoriaVeiculoId);

            if (empty($categoriaVeiculo)) {
                Resposta::response(false, "Categoria de veículo não encontrada.");
            }

            // validar se existe outro veiculo cadastrado com o mesmo renavam
            if (!empty($this->veiculoRepositorio->buscarVeiculoPeloRenavam($renavam))) {
                Resposta::response(false, "Já existe outro veiculo cadastrado com esse mesmo renavam.");
            }

            // validar se existe outro veiculo cadastrado com o mesmo número de chassi

            // validar se existe outro veiculo cadastrado com a mesma placa

            $veiculoCadastrar = new Veiculo();
            $veiculoCadastrar->modelo = strtoupper($modelo);
            $veiculoCadastrar->marca = $marca;
            $veiculoCadastrar->anoFabricacao = $anoFabricacao;
            $veiculoCadastrar->anoModelo = $anoModelo;
            $veiculoCadastrar->cor = $cor;
            $veiculoCadastrar->placa = $placa;
            $veiculoCadastrar->renavam = $renavam;
            $veiculoCadastrar->numeroChassi = $numeroChassi;
            $veiculoCadastrar->tipoCombustivel = $tipoCombustivel;
            $veiculoCadastrar->categoriaVeiculoId = $categoriaVeiculoId;
            $veiculoCadastrar->proprietarioId = $proprietarioId;

            $this->veiculoRepositorio->cadastrar($veiculoCadastrar);

            $veiculoCadastrar->proprietario = $propritarioVeiculo;
            $veiculoCadastrar->categoriaVeiculo = $categoriaVeiculo;

            Resposta::response(true, "Veiculo cadastrado com sucesso.", $veiculoCadastrar);
        } catch (Exception $e) {
            Resposta::response(false, "Erro ao tentar-se cadastrar o veículo." . $e->getMessage());
        }   

    }

    // buscar os veiculos
    public function buscarVeiculos() {

        try {
            
            if (!isset($_GET["pagina_atual"]) || !isset($_GET["elementos_por_pagina"])) {
                Resposta::response(false, "Informe a pagina atual e a quantidade de elementos por página na url.");
            }

            $paginaAtual = $_GET["pagina_atual"];
            $elementosPorPagina = $_GET["elementos_por_pagina"];

            if (empty($paginaAtual) || empty($elementosPorPagina)) {
                Resposta::response(false, "Informe a pagina atual e a quantidade de elementos por página na url.");
            }

            $veiculos = $this->veiculoRepositorio->buscarTodos($paginaAtual, $elementosPorPagina);

            if (empty($veiculos)) {
                Resposta::response(true, "Não existem veiculos cadastrados na base de dados.", []);
            }

            Resposta::response(true, "Veiculos listados com sucesso.", $veiculos);
        } catch (Exception $e) {
            Resposta::response(false, "Erro ao tentar-se buscar os veiculos.");
        }

    }

}