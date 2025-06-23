<?php

namespace Models;

class Token {

    public $tokenId;
    public $token;
    public $dataCadastro;
    public $dataLimite;
    public $usuarioId;

    public function __construct()
    {
        $this->tokenId = 0;
        $this->token = "";
        $this->dataCadastro = "";
        $this->dataLimite = "";
        $this->usuarioId = 0;
    }

}