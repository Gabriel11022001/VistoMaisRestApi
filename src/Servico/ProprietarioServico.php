<?php

namespace Servico;

use DateTime;
use Exception;
use Models\Endereco;
use Models\Proprietario;
use Repositorio\ProprietarioRepositorio;
use Utils\Resposta;
use Validators\ValidaCamposCadastroProprietario;

class ProprietarioServico extends ServicoBase {

    private $proprietarioRepositorio;

    public function __construct()
    {
        parent::__construct();

        $this->proprietarioRepositorio = new ProprietarioRepositorio($this->bancoDados);
    }

    // cadastrar proprietário na base de dados
    public function cadastrarProprietario() {
        $this->bancoDados->beginTransaction();

        try {
            $nomeCompleto =   getParametro("nome_completo");
            $cpf =            getParametro("cpf");
            $rg =             getParametro("rg");
            $email =          getParametro("email");
            $telefone =       getParametro("telefone");
            $dataNascimento = getParametro("data_nascimento");
            $numeroCnh =      getParametro("numero_cnh");

            $cep =            getParametro("cep");
            $logradouro =     getParametro("logradouro");
            $complemento =    getParametro("complemento");
            $cidade =         getParametro("cidade");
            $bairro =         getParametro("bairro");
            $estado =         getParametro("estado");
            $numero =         getParametro("numero");

            $errosCampos = ValidaCamposCadastroProprietario::validar([
                "nome_completo" => $nomeCompleto,
                "cpf" => $cpf,
                "rg" => $rg,
                "data_nascimento" => $dataNascimento,
                "email" => $email,
                "telefone" => $telefone,
                "numero_cnh" => $numeroCnh,
                "endereco" => [
                    "cep" => $cep,
                    "logradouro" => $logradouro,
                    "complemento" => $complemento,
                    "cidade" => $cidade,
                    "bairro" => $bairro,
                    "estado" => $estado,
                    "numero" => $numero
                ]
            ]);

            if (!empty($errosCampos)) {
                Resposta::response(false, "Erros nos campos.", $errosCampos);
            }

            // validar se já existe outro proprietáiro cadastrado com o mesmo cpf na base de dados
            if (!empty($this->proprietarioRepositorio->buscarPeloCpf($cpf))) {
                Resposta::response(false, "Já existe outro proprietário com esse cpf.");
            }

            // validar se existe outro proprietário cadastrado com o mesmo rg na base de dados

            // validar se existe outro priprietário cadastrado com o mesmo e-mail na base de dados

            $proprietarioCadastrar = new Proprietario();
            $endereco = new Endereco();

            $proprietarioCadastrar->nomeCompleto = strtoupper($nomeCompleto);
            $proprietarioCadastrar->cpf = $cpf;
            $proprietarioCadastrar->rg = $rg;
            $proprietarioCadastrar->email = $email;
            $proprietarioCadastrar->telefone = $telefone;
            $proprietarioCadastrar->numeroCnh = $numeroCnh;
            $proprietarioCadastrar->dataNascimento = new DateTime($dataNascimento);

            $endereco->cep = $cep;
            $endereco->logradouro = $logradouro;
            $endereco->complemento = $complemento;
            $endereco->bairro = $bairro;
            $endereco->cidade = $cidade;
            $endereco->numero = $numero;
            $endereco->estado = $estado;

            $this->proprietarioRepositorio->cadastrar($proprietarioCadastrar);

            $endereco->proprietarioId = $proprietarioCadastrar->proprietarioId;

            $this->proprietarioRepositorio->cadastrarEndereco($endereco);

            $this->bancoDados->commit();

            $proprietarioCadastrar->endereco = $endereco;

            Resposta::response(true, "Proprietário cadastrado com sucesso.", $proprietarioCadastrar);
        } catch (Exception $e) {
            $this->bancoDados->rollBack();

            Resposta::response(false, "Erro ao tentar-se cadastrar o proprietário. " . $e->getMessage());
        }

    }

    // buscar proprietarios
    public function buscarProprietarios() {

        try {
            
            if (!isset($_GET["pagina_atual"]) || !isset($_GET["elementos_por_pagina"])) {
                Resposta::response(false, "Informe a pagina atual e a quantidade de elementos por página na url.");
            }

            $paginaAtual = 1;
            $elementosPorPagina = 5;

            if (!empty($_GET["pagina_atual"]) && $_GET["pagina_atual"] > 1) {
                $paginaAtual = $_GET["pagina_atual"];
            }

            if (!empty($_GET["elementos_por_pagina"]) && $_GET["elementos_por_pagina"] >= 5 && $_GET["elementos_por_pagina"] <= 10) {
                $elementosPorPagina = $_GET["elementos_por_pagina"];
            }

            $proprietarios = $this->proprietarioRepositorio->buscarProprietarios($paginaAtual, $elementosPorPagina);

            if (count($proprietarios) > 0) {
                Resposta::response(true, "Proprietários listados com sucesso.", $proprietarios);
            } else {
                Resposta::response(true, "Não existem proprietários cadastrados na base de dados.", array());
            }

        } catch (Exception $e) {
            Resposta::response(false, "Erro ao tentar-se consultar os proprietários.", $e->getMessage());
        }

    }

    // buscar proprietário pelo cpf
    public function buscarProprietarioPeloCpf() {

        try {
            $cpf = getParametro("cpf");

            if (empty($cpf)) {
                Resposta::response(false, "Informe o cpf do proprietário.");
            }

            // validar o cpf informado

            $proprietario = $this->proprietarioRepositorio->buscarPeloCpf($cpf);

            if (empty($proprietario)) {
                Resposta::response(false, "Não existe um proprietáiro cadastrado com o cpf informado na base de dados.");
            }

            Resposta::response(true, "Proprietáiro encontrado com sucesso.", $proprietario);
        } catch (Exception $e) {
            Resposta::response(false, "Erro ao tentar-se consultar o proprietário pelo cpf.");
        }

    }

}