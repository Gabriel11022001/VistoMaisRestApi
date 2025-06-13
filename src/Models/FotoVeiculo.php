<?php

namespace Models;

class FotoVeiculo {
    
    public $fotoVeiculoId;
    public $fotoUrl;
    public $veiculoId;

    public function __construct()
    {
        $this->fotoVeiculoId = 0;
        $this->fotoUrl = "";
        $this->veiculoId = 0;
    }

}