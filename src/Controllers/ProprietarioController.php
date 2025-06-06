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

    // buscar todos os proprietários
    public function buscarProprietarios() {

        return $this->proprietarioServico->buscarProprietarios();
    }

    // buscar proprietário pelo cpf
    public function buscarProprietarioPeloCpf() {

        return $this->proprietarioServico->buscarProprietarioPeloCpf();
    }

}