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

}