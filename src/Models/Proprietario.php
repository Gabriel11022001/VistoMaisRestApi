<?php

namespace Models;

class Proprietario {

    public $proprietarioId;
    public $nomeCompleto;
    public $telefone;
    public $email;
    public $cpf;
    public $dataNascimento;
    public $rg;
    public $numeroCnh;
    public $fotoFrenteDocumento;
    public $fotoVersoDocumento;
    public $fotoProprietario;
    public $fotoComprovanteResidencia;
    public $endereco;

    public function __construct()
    {
        $this->proprietarioId = 0;
        $this->nomeCompleto = "";
        $this->cpf = "";
        $this->dataNascimento = null;
        $this->rg = "";
        $this->numeroCnh = "";
        $this->fotoFrenteDocumento = "";
        $this->fotoVersoDocumento = "";
        $this->fotoProprietario = "";
        $this->fotoComprovanteResidencia = "";
        $this->endereco = null;
        $this->telefone = "";
        $this->email = "";
    }

}