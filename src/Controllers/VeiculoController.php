<?php

namespace Controllers;

use Servico\VeiculoServico;

class VeiculoController {

    private $veiculoServico;

    public function __construct()
    {
        $this->veiculoServico = new VeiculoServico();
    }

    // cadastrar veiculo na base de dados
    public function cadastrarVeiculo() {

        return $this->veiculoServico->cadastrarVeiculo();
    }

    // buscar veiculos de forma paginada
    public function buscarVeiculos() {

        return $this->veiculoServico->buscarVeiculos();
    }

}