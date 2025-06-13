<?php

namespace Models;

class Veiculo {

    public $veiculoId;
    public $marca;
    public $cor;
    public $modelo;
    public $anoFabricacao;
    public $anoModelo;
    public $placa;
    public $numeroChassi;
    public $renavam;
    public $tipoCombustivel;
    public $categoriaVeiculoId;
    public $categoriaVeiculo;
    public $proprietarioId;
    public $proprietario;
    public $fotosVeiculo;

    public function __construct()
    {
        $this->veiculoId = 0;
        $this->modelo = "";
        $this->marca = "";
        $this->cor = "";
        $this->anoFabricacao = 0;
        $this->anoModelo = 0;
        $this->placa = "";
        $this->numeroChassi = "";
        $this->renavam = "";
        $this->tipoCombustivel = "";
        $this->categoriaVeiculo = null;
        $this->categoriaVeiculoId = 0;
        $this->proprietario = null;
        $this->proprietarioId = 0;
        $this->fotosVeiculo = array();
    }

}