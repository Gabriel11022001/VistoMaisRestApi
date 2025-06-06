<?php

namespace Repositorio;

use DateTime;
use Exception;
use Models\Endereco;
use Models\Proprietario;
use PDO;

class ProprietarioRepositorio extends Repositorio implements IProprietarioRepositorio {

    public function __construct($conexaoBancoDados)
    {
        parent::__construct($conexaoBancoDados);
    }

    // buscar proprietários no banco de dados
    public function buscarProprietarios($paginaAtual, $elementosPorPagina) {
        $stmt = $this->bancoDados->prepare("SELECT p.*, e.cep, e.endereco_id, e.complemento, e.bairro, e.logradouro,
        e.estado, e.cidade, e.numero
        FROM tb_proprietarios AS p
        INNER JOIN tb_enderecos AS e
        ON p.proprietario_id = e.proprietario_id
        ORDER BY p.nome_completo ASC");

        $stmt->execute();
        $proprietariosArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $proprietarios = array();

        foreach ($proprietariosArray as $proprietarioArray) {
            $proprietario = new Proprietario();
            $endereco = new Endereco();

            $proprietario->proprietarioId = $proprietarioArray["proprietario_id"];
            $proprietario->nomeCompleto = $proprietarioArray["nome_completo"];
            $proprietario->telefone = $proprietarioArray["telefone"];
            $proprietario->email = $proprietarioArray["email"];
            $proprietario->dataNascimento = new DateTime($proprietarioArray["data_nascimento"]);
            $proprietario->rg = $proprietarioArray["rg"];
            $proprietario->cpf = $proprietarioArray["cpf"];
            $proprietario->numeroCnh = $proprietarioArray["numero_cnh"];
            $proprietario->fotoProprietario = $proprietarioArray["foto_proprietario"];
            $proprietario->fotoFrenteDocumento = $proprietarioArray["foto_frente_documento"];
            $proprietario->fotoVersoDocumento = $proprietarioArray["foto_verso_documento"];
            $proprietario->fotoComprovanteResidencia = $proprietarioArray["foto_comprovante_residencia"];

            $endereco->enderecoId = $proprietarioArray["endereco_id"];
            $endereco->cep = $proprietarioArray["cep"];
            $endereco->complemento = $proprietarioArray["complemento"];
            $endereco->logradouro = $proprietarioArray["logradouro"];
            $endereco->bairro = $proprietarioArray["bairro"];
            $endereco->cidade = $proprietarioArray["cidade"];
            $endereco->numero = $proprietarioArray["numero"];
            $endereco->estado = $proprietarioArray["estado"];

            $proprietario->endereco = $endereco;

            $proprietarios[] = $proprietario;
        }

        return $proprietarios;
    }

    // cadastrar proprietário na base de dados
    public function cadastrar($proprietarioCadastrar) {
        $stmt = $this->bancoDados->prepare("INSERT INTO tb_proprietarios(nome_completo, telefone, email, cpf, rg,
        data_nascimento, numero_cnh) VALUES(:nome_completo, :telefone, :email, :cpf, :rg,
        :data_nascimento, :numero_cnh)");

        $stmt->bindValue(":nome_completo", $proprietarioCadastrar->nomeCompleto);
        $stmt->bindValue(":telefone", $proprietarioCadastrar->telefone);
        $stmt->bindValue(":email", $proprietarioCadastrar->email);
        $stmt->bindValue(":cpf", $proprietarioCadastrar->cpf);
        $stmt->bindValue(":rg", $proprietarioCadastrar->rg);
        $stmt->bindValue(":data_nascimento", $proprietarioCadastrar->dataNascimento->format("Y-m-d"));
        $stmt->bindValue(":numero_cnh", $proprietarioCadastrar->numeroCnh);

        if (!$stmt->execute()) {

            throw new Exception("Erro ao tentar-se cadastrar o proprietário na base de dados.");
        }

        $proprietarioCadastrar->proprietarioId = $this->bancoDados->lastInsertId();
    }

    public function editar($proprietarioEditar) {
        
    }

    // buscar proprietário pelo cpf
    public function buscarPeloCpf($cpfProprietario) {
        $stmt = $this->bancoDados->prepare("SELECT p.proprietario_id, p.nome_completo, p.cpf,
        p.rg, p.data_nascimento, p.telefone, p.email, p.numero_cnh, e.endereco_id, e.cep, e.complemento,
        e.logradouro, e.bairro, e.cidade, e.estado, e.numero
        FROM tb_proprietarios AS p
        INNER JOIN tb_enderecos AS e
        ON p.proprietario_id = e.proprietario_id
        AND p.cpf = :cpf");

        $stmt->bindValue(":cpf", $cpfProprietario);
        $stmt->execute();
        $proprietarioArray = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (empty($proprietarioArray)) {

            return null;
        }

        $proprietario = new Proprietario();
        $proprietario->proprietarioId = $proprietarioArray["proprietario_id"];
        $proprietario->nomeCompleto = $proprietarioArray["nome_completo"];
        $proprietario->telefone = $proprietarioArray["telefone"];
        $proprietario->cpf = $proprietarioArray["cpf"];
        $proprietario->rg = $proprietarioArray["rg"];
        $proprietario->email = $proprietarioArray["email"];
        $proprietario->dataNascimento = new DateTime($proprietarioArray["data_nascimento"]);
        $proprietario->numeroCnh = $proprietarioArray["numero_cnh"];
        $endereco = new Endereco();
        $endereco->enderecoId = $proprietarioArray["endereco_id"];
        $endereco->cep = $proprietarioArray["cep"];
        $endereco->complemento = $proprietarioArray["complemento"];
        $endereco->logradouro = $proprietarioArray["logradouro"];
        $endereco->bairro = $proprietarioArray["bairro"];
        $endereco->cidade = $proprietarioArray["cidade"];
        $endereco->numero = $proprietarioArray["numero"];
        $endereco->estado = $proprietarioArray["estado"];
        $proprietario->endereco = $endereco;

        return $proprietario;
    }

    public function atualizarFotoComprovanteResidencia($fotoComprovanteResidencia, $proprietarioId) {
        
    }

    public function atualizarFotoFrenteDocumento($fotoFrenteDocumento, $proprietarioId) {
        
    }

    public function atualizarFotoVersoDocumento($fotoVersoDocumento, $proprietarioId) {
        
    }

    public function atualizarFotoProprietario($fotoProprietario, $proprietarioId) {
        
    }

    public function buscarPeloEmail($emailProprietario) {
        
    }

    public function buscarPeloRg($rgProprietario) {
        
    }

    // buscar proprietário pelo id
    public function buscarPeloId($idProprietarioConsultar) {
        $stmt = $this->bancoDados->prepare("SELECT p.*, e.endereco_id, e.cep, e.logradouro, e.complemento,
        e.cidade, e.bairro, e.estado, e.numero
        FROM tb_proprietarios AS p
        INNER JOIN tb_enderecos AS e
        ON p.proprietario_id = e.proprietario_id
        AND p.proprietario_id = :proprietario_id");

        $stmt->bindValue(":proprietario_id", $idProprietarioConsultar);
        $stmt->execute();
        $proprietarioObj = $stmt->fetch(PDO::FETCH_OBJ);

        if (empty($proprietarioObj)) {

            return null;
        }

        $proprietario = new Proprietario();
        $endereco = new Endereco();

        $proprietario->proprietarioId = $proprietarioObj->proprietario_id;
        $proprietario->nomeCompleto = $proprietarioObj->nome_completo;
        $proprietario->telefone = $proprietarioObj->telefone;
        $proprietario->cpf = $proprietarioObj->cpf;
        $proprietario->rg = $proprietarioObj->rg;
        $proprietario->email = $proprietarioObj->email;
        $proprietario->dataNascimento = new DateTime($proprietarioObj->data_nascimento);
        $proprietario->numeroCnh = $proprietarioObj->numero_cnh;
        
        $endereco->enderecoId = $proprietarioObj->endereco_id;
        $endereco->cep = $proprietarioObj->cep;
        $endereco->complemento = $proprietarioObj->complemento;
        $endereco->logradouro = $proprietarioObj->logradouro;
        $endereco->cidade = $proprietarioObj->cidade;
        $endereco->bairro = $proprietarioObj->bairro;
        $endereco->numero = $proprietarioObj->numero;
        $endereco->estado = $proprietarioObj->estado;

        $proprietario->endereco = $endereco;

        return $proprietario;
    }

    // cadastrar endereço do proprietário
    public function cadastrarEndereco($enderecoProprietarioCadastrar) {
        $stmt = $this->bancoDados->prepare("INSERT INTO tb_enderecos(cep, logradouro, complemento, bairro, cidade,
        estado, numero, proprietario_id) VALUES(:cep, :logradouro, :complemento, :bairro, :cidade, :estado, :numero, :proprietario_id)");

        $stmt->bindValue(":cep", $enderecoProprietarioCadastrar->cep);
        $stmt->bindValue(":logradouro", $enderecoProprietarioCadastrar->logradouro);
        $stmt->bindValue(":complemento", $enderecoProprietarioCadastrar->complemento);
        $stmt->bindValue(":bairro", $enderecoProprietarioCadastrar->bairro);
        $stmt->bindValue(":cidade", $enderecoProprietarioCadastrar->cidade);
        $stmt->bindValue(":estado", $enderecoProprietarioCadastrar->estado);
        $stmt->bindValue(":numero", $enderecoProprietarioCadastrar->numero);
        $stmt->bindValue(":proprietario_id", $enderecoProprietarioCadastrar->proprietarioId);

        if (!$stmt->execute()) {

            throw new Exception("Erro ao tentar-se cadastrar o endereço do proprietário.");
        }

        $enderecoProprietarioCadastrar->enderecoId = $this->bancoDados->lastInsertId();
    }

    // editar endereço do proprietário
    public function editarEndereco($enderecoProprietarioEditar) {
        $stmt = $this->bancoDados->prepare("UPDATE tb_enderecos SET cep = :cep, logradouro = :logradouro,
        complemento = :complemento, bairro = :bairro, cidade = :cidade, estado = :estado, numero = :numero,
        proprietario_id = :proprietario_id
        WHERE endereco_id = :endereco_id");

        $stmt->bindValue(":endereco_id", $enderecoProprietarioEditar->enderecoId);
        $stmt->bindValue(":cep", $enderecoProprietarioEditar->cep);
        $stmt->bindValue(":logradouro", $enderecoProprietarioEditar->logradouro);
        $stmt->bindValue(":complemento", $enderecoProprietarioEditar->complemento);
        $stmt->bindValue(":bairro", $enderecoProprietarioEditar->bairro);
        $stmt->bindValue(":cidade", $enderecoProprietarioEditar->cidade);
        $stmt->bindValue(":numero", $enderecoProprietarioEditar->numero);
        $stmt->bindValue(":estado", $enderecoProprietarioEditar->estado);
        $stmt->bindValue(":proprietario_id", $enderecoProprietarioEditar->proprietarioId);

        if (!$stmt->execute()) {

            throw new Exception("Erro ao tentar-se editar o endereço do proprietário.");
        }

    }

}