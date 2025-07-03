<?php

namespace Controllers;

use Servico\VistoriaVeicularServico;

class VistoriaVeicularController {

    private $vistoriaVeicularServico;

    public function __construct()
    {
        $this->vistoriaVeicularServico = new VistoriaVeicularServico();
    }

    // cadastrar vistoria veicular na base de dados
    public function cadastrarVistoriaVeicular() {

        return $this->vistoriaVeicularServico->cadastrarVistoriaVeicular();
    }

    // buscar vistoria veicular pelo id
    public function buscarVistoriaVeicularPeloId() {

        return $this->vistoriaVeicularServico->buscarVistoriaVeicularPeloId();
    }

    // buscar vistorias veiculares
    public function buscarVistoriasVeiculares() {

        return $this->vistoriaVeicularServico->buscarVistoriasVeiculares();
    }

    // deletar vistoria veicular
    public function deletarVistoriaVeicular() {

        return $this->vistoriaVeicularServico->deletarVistoriaVeicular();
    }

}