<?php

namespace Models;

class VistoriaVeicular extends Vistoria {

    public $veiculoId;
    public $nomeVistoriador;
    public $pneusNaoEstaoDesgastados;
    public $pressaoPneusCorreto;
    public $faroisPerfeitoFuncionamento;
    public $lanternasPerfeitoFuncionamento;
    public $setasPerfeitoFuncionamento;
    public $sistemaFreiosPerfeitoFuncionamento;
    public $motorPerfeitoFuncionamento;
    public $cintoSegurancaPerfeitoFuncionamento;
    public $possuiTrianguloSinalizacao;
    public $extintorIncendioEstaDataValidade;
    public $extintorIncendioPerfeitoFuncionamento;
    public $observacoes;
    public $recomendacoes;
    public $classificacaoGeralVeiculo;
    public $veiculo;

    public function __construct()
    {
        parent::__construct();

        $this->veiculoId = 0;
        $this->nomeVistoriador = "";
        $this->pneusNaoEstaoDesgastados = true;
        $this->pressaoPneusCorreto = true;
        $this->faroisPerfeitoFuncionamento = true;
        $this->lanternasPerfeitoFuncionamento = true;
        $this->setasPerfeitoFuncionamento = true;
        $this->sistemaFreiosPerfeitoFuncionamento = true;
        $this->motorPerfeitoFuncionamento = true;
        $this->cintoSegurancaPerfeitoFuncionamento = true;
        $this->possuiTrianguloSinalizacao = true;
        $this->extintorIncendioEstaDataValidade = true;
        $this->extintorIncendioPerfeitoFuncionamento = true;
        $this->observacoes = "";
        $this->recomendacoes = "";
        $this->classificacaoGeralVeiculo = "";
        $this->veiculo = null;
    }

}