<?php

namespace Models;

class Vistoria {
    
    public $vistoriaId;
    public $dataRealizacao;
    public $status;

    public function __construct()
    {
        $this->vistoriaId = 0;
        $this->dataRealizacao = null;
        $this->status = "";
    }

}