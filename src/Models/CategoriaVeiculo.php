<?php

namespace Models;

class CategoriaVeiculo {

    public $categoriaId;
    public $nomeCategoria;

    public function __construct()
    {
        $this->categoriaId = 0;
        $this->nomeCategoria = "";
    }

}