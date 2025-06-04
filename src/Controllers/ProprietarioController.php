<?php

namespace Controllers;

use Servico\ProprietarioServico;

class ProprietarioController {

    private $proprietarioServico;

    public function __construct()
    {
        $this->proprietarioServico = new ProprietarioServico();
    }

    // cadastrar proprietário
    public function cadastrarProprietario() {

        return $this->proprietarioServico->cadastrarProprietario();
    }

}