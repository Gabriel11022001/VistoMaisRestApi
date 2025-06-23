<?php

namespace Servico;

use DateTime;
use Exception;
use Models\VistoriaVeicular;
use Repositorio\VeiculoRepositorio;
use Repositorio\VistoriaVeicularRepositorio;
use Utils\Resposta;

class VistoriaVeicularServico extends ServicoBase {
    
    private $vistoriaVeicularRepositorio;
    private $veiculoRepositorio;
    private $classificacoes = [
        "boa" => 70,
        "media" => 50,
        "ruim" => 0
    ];

    public function __construct()
    {
        parent::__construct();

        $this->vistoriaVeicularRepositorio = new VistoriaVeicularRepositorio($this->bancoDados);
        $this->veiculoRepositorio = new VeiculoRepositorio($this->bancoDados);
    }

    // validar campos no cadastro de vistoria veicular
    private function validarCamposCadastroVistoriaVeicular($camposValidar = array()) {
        $erros = [];

        if (empty($camposValidar["veiculo_id"])) {
            $erros["veiculo_id"] = "Informe o id do veiculo.";
        }

        if (empty($camposValidar["nome_vistoriador"])) {
            $erros["nome_vistoriador"] = "Informe o nome do vistoriador.";
        } elseif (strlen($camposValidar["nome_vistoriador"]) < 3) {
            $erros["nome_vistoriador"] = "O nome do vistoriador deve possuir no mínimo 3 caracteres.";
        }

        return $erros;
    }

    // cadastrar vistoria veicular
    public function cadastrarVistoriaVeicular() {

        try {
            $veiculoId = getParametro("veiculo_id");
            $nomeVistoriador = getParametro("nome_vistoriador");
            $pneusNaoEstaoDesgastados = getParametro("pneus_nao_estao_desgastados");
            $pressaoPneusCorretos = getParametro("pressao_pneus_corretos");
            $faroisPerfeitoFuncionamento = getParametro("farois_perfeito_funcionamento");
            $lanternasPerfeitoFuncionamento = getParametro("lanternas_perfeito_funcionamento");
            $setasPerfeitoFuncionamento = getParametro("setas_perfeito_funcionamento");
            $sistemaFreiosPerfeitoFuncionamento = getParametro("sistemas_freios_perfeito_funcionamento");
            $motorPerfeitoFuncionamento = getParametro("motor_perfeito_funcionamento");
            $cintoSegurancaPerfeitoFuncionamento = getParametro("cinto_seguranca_perfeito_funcionamento");
            $possuiTrianguloSinalizacao = getParametro("possui_triangulo_sinalizacao");
            $extintorIncendioEstaDataValidade = getParametro("extintor_incendio_esta_data_validade");
            $extintorIncendioPerfeitoFuncionamento = getParametro("extintor_incendio_perfeito_funcionamento");
            $observacoes = getParametro("observacoes");
            $recomendacoes = getParametro("recomendacoes");
            $classificacaoGeralVeiculo = "";
            $totalValidacoes = 11;

            $errosCampos = $this->validarCamposCadastroVistoriaVeicular([
                "veiculo_id" => $veiculoId,
                "nome_vistoriador" => $nomeVistoriador
            ]);

            if (!empty($errosCampos)) {
                Resposta::response(false, "Erros nos campos.", $errosCampos);
            }

            // validar se existe um veiculo cadastrado com o id informado
            if (empty($this->veiculoRepositorio->buscarPeloId($veiculoId))) {
                Resposta::response(false, "Não existe um veiculo cadastrado com o id informado na base de dados.");
            }

            $totalValidacoesBoas = $this->getTotalValidacoesBoas([
                $pneusNaoEstaoDesgastados,
                $pressaoPneusCorretos,
                $faroisPerfeitoFuncionamento,
                $lanternasPerfeitoFuncionamento,
                $setasPerfeitoFuncionamento,
                $sistemaFreiosPerfeitoFuncionamento,
                $motorPerfeitoFuncionamento,
                $cintoSegurancaPerfeitoFuncionamento,
                $possuiTrianguloSinalizacao,
                $extintorIncendioEstaDataValidade,
                $extintorIncendioPerfeitoFuncionamento
            ]);

            $percentualValidacoesBoas = $this->calcularPercentualValidacoesBoas($totalValidacoesBoas, $totalValidacoes);

            if ($percentualValidacoesBoas >= $this->classificacoes["boa"]) {
                $classificacaoGeralVeiculo = "Boa";
            } else if ($percentualValidacoesBoas >= $this->classificacoes["media"]) {
                $classificacaoGeralVeiculo = "Média";
            } else {
                $classificacaoGeralVeiculo = "Ruim";
            }

            $vistoriaVeicular = new VistoriaVeicular();

            $vistoriaVeicular->veiculoId = $veiculoId;
            $vistoriaVeicular->nomeVistoriador = $nomeVistoriador;
            $vistoriaVeicular->pneusNaoEstaoDesgastados = $pneusNaoEstaoDesgastados;
            $vistoriaVeicular->pressaoPneusCorreto = $pressaoPneusCorretos;
            $vistoriaVeicular->faroisPerfeitoFuncionamento = $faroisPerfeitoFuncionamento;
            $vistoriaVeicular->lanternasPerfeitoFuncionamento = $lanternasPerfeitoFuncionamento;
            $vistoriaVeicular->setasPerfeitoFuncionamento = $setasPerfeitoFuncionamento;
            $vistoriaVeicular->sistemaFreiosPerfeitoFuncionamento = $sistemaFreiosPerfeitoFuncionamento;
            $vistoriaVeicular->motorPerfeitoFuncionamento = $motorPerfeitoFuncionamento;
            $vistoriaVeicular->cintoSegurancaPerfeitoFuncionamento = $cintoSegurancaPerfeitoFuncionamento;
            $vistoriaVeicular->possuiTrianguloSinalizacao = $possuiTrianguloSinalizacao;
            $vistoriaVeicular->extintorIncendioEstaDataValidade = $extintorIncendioEstaDataValidade;
            $vistoriaVeicular->extintorIncendioPerfeitoFuncionamento = $extintorIncendioPerfeitoFuncionamento;
            $vistoriaVeicular->observacoes = $observacoes;
            $vistoriaVeicular->recomendacoes = $recomendacoes;
            $vistoriaVeicular->classificacaoGeralVeiculo = $classificacaoGeralVeiculo;
            $vistoriaVeicular->dataRealizacao = new DateTime("now");
            $vistoriaVeicular->status = "Finalizada";

            // cadastrar vistoria na base de dados
            $this->vistoriaVeicularRepositorio->cadastrarVistoriaVeicular($vistoriaVeicular);

            Resposta::response(true, "Vistoria finalizada com sucesso.", $vistoriaVeicular);
        } catch (Exception $e) {
            Resposta::response(false, "Erro ao tentar-se cadastrar a vistoria veicular." . $e->getMessage());
        }

    }

    // obter o total de validações boas
    private function getTotalValidacoesBoas($validacoes = []) {
        $totalValidacoesBoas = 0;

        foreach ($validacoes as $validacao) {

            if ($validacao) {
                $totalValidacoesBoas++;
            }

        }

        return $totalValidacoesBoas;
    }

    // obter o percentual de validações boas
    private function calcularPercentualValidacoesBoas($totalValidacoesBoas, $totalValidacoesFazer) {

        if ($totalValidacoesBoas == 0) {

            return 0;
        }

        return ($totalValidacoesBoas / $totalValidacoesFazer) * 100;
    }

    // buscar vistoria veicular pelo id
    public function buscarVistoriaVeicularPeloId() {

        try {

            if (!isset($_GET["vistoria_id"])) {
                Resposta::response(false, "Informe o id da vistoria na url.");
            }

            $id = $_GET["vistoria_id"];

            if (empty($id)) {
                Resposta::response(false, "Informe o id da vistoria.");
            }

            $vistoria = $this->vistoriaVeicularRepositorio->buscarVistoriaVeicularPeloId($id);

            if (empty($vistoria)) {
                Resposta::response(false, "Vistoria não encontrada.");
            }

            Resposta::response(true, "Vistoria encontrada com sucesso.", $vistoria);
        } catch (Exception $e) {
            Resposta::response(false, "Erro ao tentar-se buscar a vistoria veicular pelo id." . $e->getMessage());
        }

    }

}