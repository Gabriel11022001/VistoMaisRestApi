<?php

namespace Models;

class Endereco {

    public $enderecoId;
    public $cep;
    public $logradouro;
    public $complemento;
    public $bairro;
    public $cidade;
    public $estado;
    public $numero;
    public $proprietarioId;

    public function __construct()
    {
        $this->cep = "";
        $this->logradouro = "";
        $this->complemento = "";
        $this->bairro = "";
        $this->cidade = "";
        $this->numero = "";
        $this->estado = "";
        $this->proprietarioId = 0;
    }

}
