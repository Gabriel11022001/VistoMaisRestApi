<?php

namespace Controllers;

use Servico\UsuarioServico;

class UsuarioController {

    private $usuarioServico;

    public function __construct()
    {
        $this->usuarioServico = new UsuarioServico();
    }

    // cadastrar usuário na base de dados
    public function cadastrarUsuario() {

        return $this->usuarioServico->cadastrarUsuario();
    }

}