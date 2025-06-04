<?php

namespace Controllers;

use Servico\CategoriaVeiculoServico;

class CategoriaVeiculoController {

    private $categoriaVeiculoServico;

    public function __construct()
    {
        $this->categoriaVeiculoServico = new CategoriaVeiculoServico();
    }

    // cadastrar categoria de veiculo 
    public function cadastrarCategoriaVeiculo() {

        return $this->categoriaVeiculoServico->cadastrarCategoriaVeiculo();
    }

    // listar todas as categorias de veiculo
    public function listarCategoriasVeiculos() {

        return $this->categoriaVeiculoServico->listarCategoriasVeiculos();
    }

    // editar categoria de veiculo
    public function editarCategoriaVeiculo() {

        return $this->categoriaVeiculoServico->editarCategoriaVeiculo();
    }

    // buscar categoria do veiculo pelo id
    public function buscarCategoriaVeiculoPeloId() {

        return $this->categoriaVeiculoServico->buscarCategoriaVeiculoPeloId();
    }

}