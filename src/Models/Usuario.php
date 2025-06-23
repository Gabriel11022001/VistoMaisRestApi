<?php

namespace Models;

class Usuario {

    public $usuarioId;
    public $nomeCompleto;
    public $login;
    public $senha;
    public $token;

    public function __construct()
    {
        $this->usuarioId = 0;
        $this->nomeCompleto = "";
        $this->login = "";
        $this->senha = "";
        $this->token = null;
    }

}