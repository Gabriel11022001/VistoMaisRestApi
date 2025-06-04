<?php

namespace Servico;

use Exception;
use Models\CategoriaVeiculo;
use Repositorio\CategoriaVeiculoRepositorio;
use Utils\Resposta;

class CategoriaVeiculoServico extends ServicoBase {

    private $categoriaVeiculoRepositorio;

    public function __construct()
    {
        parent::__construct();

        $this->categoriaVeiculoRepositorio = new CategoriaVeiculoRepositorio($this->bancoDados);
    }

    // cadastrar categoria de veiculo
    public function cadastrarCategoriaVeiculo() {

        try {
            $nomeCategoria = getParametro("nome_categoria");

            if (empty($nomeCategoria)) {
                Resposta::response(false, "Informe o nome da categoria.");
            }

            if (strlen($nomeCategoria) < 3) {
                Resposta::response(false, "O nome da categoria do produto deve possuir no mínino 3 caracteres.");
            }

            // validar se existe outra categoria de veiculo cadastrada com o mesmo nome
            if (!empty($this->categoriaVeiculoRepositorio->buscarPeloNome(strtoupper($nomeCategoria)))) {
                Resposta::response(false, "Já existe outra categoria cadastrada com esse mesmo nome na base de dados.");
            }

            $categoriaCadastrar = new CategoriaVeiculo();
            $categoriaCadastrar->nomeCategoria = strtoupper($nomeCategoria);

            $this->categoriaVeiculoRepositorio->cadastrar($categoriaCadastrar);

            Resposta::response(true, "Categoria cadastrada com sucesso.", $categoriaCadastrar);
        } catch (Exception $e) {
            Resposta::response(false, "Erro ao tentar-se cadastrar a categoria de veículo.");
        }

    }

    // buscar todas as categorias de veiculo
    public function listarCategoriasVeiculos() {

        try {
            $categorias = $this->categoriaVeiculoRepositorio->listarTodos();

            if (count($categorias) == 0) {
                Resposta::response(true, "Não existem categorias cadastradas na base de dados.", []);
            }

            Resposta::response(true, "Categorias listadas com sucesso.", $categorias);
        } catch (Exception $e) {
            Resposta::response(false, "Erro ao tentar-se listar as categorias dos veiculos.");
        }

    }

    // editar categoria de veiculo
    public function editarCategoriaVeiculo() {

        try {
            $categoriaId = getParametro("categoria_id");
            $categoriaNome = getParametro("categoria_nome");
            $errosCampos = [];

            if (empty($categoriaId)) {
                $errosCampos["categoria_id"] = "Informe o id da categoria.";
            }

            if (empty($categoriaNome)) {
                $errosCampos["categoria_nome"] = "Informe o nome da categoria.";
            } else if (strlen($categoriaNome) < 3) {
                $errosCampos["categoria_nome"] = "O nome da categoria deve possuir no mínimo 3 caracteres.";
            }

            if (!empty($errosCampos)) {
                Resposta::response(false, "Erros nos campos.", $errosCampos);
            }

            if (empty($this->categoriaVeiculoRepositorio->buscarPeloId($categoriaId))) {
                Resposta::response(false, "Não existe uma categoria cadastrada com o id informado.");
            }

            $categoriaCadastradaMesmoNome = $this->categoriaVeiculoRepositorio->buscarPeloNome(strtoupper($categoriaNome));

            if (!empty($categoriaCadastradaMesmoNome) && $categoriaCadastradaMesmoNome->categoriaId != $categoriaId) {
                Resposta::response(false, "Já existe outra categoria cadastrada com o mesmo nome na base de dados.");
            }

            $categoriaEditar = new CategoriaVeiculo();
            $categoriaEditar->categoriaId = $categoriaId;
            $categoriaEditar->nomeCategoria = strtoupper($categoriaNome);

            $this->categoriaVeiculoRepositorio->editar($categoriaEditar);

            Resposta::response(true, "Categoria editada com sucesso.", $categoriaEditar);
        } catch (Exception $e) {
            Resposta::response(false, "Erro ao tentar-se editar a categoria.");
        }

    }

    // buscar categoria veiculo pelo id
    public function buscarCategoriaVeiculoPeloId() {

        try {

            if (!isset($_GET["categoria_veiculo_id"])) {
                Resposta::response(false, "Informe o id da categoria do veiculo no cabeçalho da requisição.");
            }

            if (empty($_GET["categoria_veiculo_id"])) {
                Resposta::response(false, "Informe o id da categoria do veiculo.");
            }

            $categoriaVeiculoId = $_GET["categoria_veiculo_id"];

            $categoria = $this->categoriaVeiculoRepositorio->buscarPeloId($categoriaVeiculoId);

            if (empty($categoria)) {
                Resposta::response(false, "Categoria de veiculo não encontrado.");
            }

            Resposta::response(true, "Categoria de veiculo encontrado com sucesso.", $categoria);
        } catch (Exception $e) {
            Resposta::response(false, "Erro ao tentar-se buscar a categoria do veiculo pelo id.");
        }

    }

}