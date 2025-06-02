<?php

namespace Repositorio;

use Exception;
use Models\CategoriaVeiculo;
use PDO;

class CategoriaVeiculoRepositorio extends Repositorio implements ICategoriaVeiculoRepositorio {

    public function __construct($conexaoBancoDados)
    {
        parent::__construct($conexaoBancoDados);
    }

    // cadastrar categoria de veículo
    public function cadastrar($categoriaVeiculoCadastrar) {
        $stmt = $this->bancoDados->prepare("INSERT INTO tb_categorias_veiculos(nome_categoria) VALUES(:nome_categoria)");
        $stmt->bindValue(":nome_categoria", $categoriaVeiculoCadastrar->nomeCategoria);

        if (!$stmt->execute()) {

            throw new Exception("Erro ao tentar-se cadastrar a categoria do veiculo na base de dados.");
        }

        $categoriaVeiculoCadastrar->categoriaId = $this->bancoDados->lastInsertId();
    }

    // editar categoria de veiculo
    public function editar($categoriaVeiculoEditar) {
        $stmt = $this->bancoDados->prepare("UPDATE tb_categorias_veiculos SET nome_categoria = :nome_categoria
        WHERE categoria_veiculo_id = :categoria_veiculo_id");
        $stmt->bindValue(":categoria_veiculo_id", $categoriaVeiculoEditar->categoriaId);
        $stmt->bindValue(":nome_categoria", $categoriaVeiculoEditar->nomeCategoria);

        if (!$stmt->execute()) {

            throw new Exception("Erro ao tentar-se editar a categoria.");
        }

    }

    // listar todas as categorias de veiculos
    public function listarTodos() {
        $stmt = $this->bancoDados->prepare("SELECT * FROM tb_categorias_veiculos");
        $stmt->execute();
        $categoriasArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $categorias = array();

        foreach ($categoriasArray as $categoriaArray) {
            $categoria = new CategoriaVeiculo();
            $categoria->categoriaId = $categoriaArray["categoria_veiculo_id"];
            $categoria->nomeCategoria = $categoriaArray["nome_categoria"];
            $categorias[] = $categoria;
        }

        return $categorias;
    }

    public function deletar($categoriaVeiculoId) {
        
    }

    // buscar categoria de veiculo pelo id
    public function buscarPeloId($categoriaVeiculoId) {
        $stmt = $this->bancoDados->prepare("SELECT * FROM tb_categorias_veiculos WHERE categoria_veiculo_id = :categoria_veiculo_id");
        $stmt->bindValue(":categoria_veiculo_id", $categoriaVeiculoId);
        $stmt->execute();
        $categoriaArray = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($categoriaArray)) {

            return null;
        }

        $categoria = new CategoriaVeiculo();
        $categoria->categoriaId = $categoriaArray["categoria_veiculo_id"];
        $categoria->nomeCategoria = $categoriaArray["nome_categoria"];

        return $categoria;
    }

    // buscar categoria do produto pelo nome
    public function buscarPeloNome($nomeCategoria) {
        $stmt = $this->bancoDados->prepare("SELECT * FROM tb_categorias_veiculos WHERE nome_categoria = :nome_categoria");
        $stmt->bindValue(":nome_categoria", $nomeCategoria);
        $stmt->execute();
        $categoriaArray = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($categoriaArray)) {

            return null;
        }

        $categoria = new CategoriaVeiculo();
        $categoria->categoriaId = $categoriaArray["categoria_veiculo_id"];
        $categoria->nomeCategoria = $categoriaArray["nome_categoria"];

        return $categoria;
    }

}