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

}