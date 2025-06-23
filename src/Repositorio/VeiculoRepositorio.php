<?php

namespace Repositorio;

use DateTime;
use Exception;
use Models\CategoriaVeiculo;
use Models\FotoVeiculo;
use Models\Proprietario;
use Models\Veiculo;
use PDO;

class VeiculoRepositorio extends Repositorio implements IVeiculoRepositorio {

    public function __construct($bancoDados)
    {
        parent::__construct($bancoDados);
    }

    // cadastrar veículo na base de dados
    public function cadastrar($veiculoCadastrar) {
        $stmt = $this->bancoDados->prepare("INSERT INTO tb_veiculos(marca, modelo, ano_fabricacao, ano_modelo, cor,
        placa, numero_chassi, renavam, tipo_combustivel, categoria_veiculo_id, proprietario_id)
        VALUES(:marca, :modelo, :ano_fabricacao, :ano_modelo, :cor, :placa, :numero_chassi, :renavam,
        :tipo_combustivel, :categoria_veiculo_id, :proprietario_id)");

        $stmt->bindValue(":marca", $veiculoCadastrar->marca);
        $stmt->bindValue(":modelo", $veiculoCadastrar->modelo);
        $stmt->bindValue(":ano_fabricacao", $veiculoCadastrar->anoFabricacao);
        $stmt->bindValue(":ano_modelo", $veiculoCadastrar->anoModelo);
        $stmt->bindValue(":cor", $veiculoCadastrar->cor);
        $stmt->bindValue(":placa", $veiculoCadastrar->placa);
        $stmt->bindValue(":numero_chassi", $veiculoCadastrar->numeroChassi);
        $stmt->bindValue(":renavam", $veiculoCadastrar->renavam);
        $stmt->bindValue(":tipo_combustivel", $veiculoCadastrar->tipoCombustivel);
        $stmt->bindValue(":categoria_veiculo_id", $veiculoCadastrar->categoriaVeiculoId);
        $stmt->bindValue(":proprietario_id", $veiculoCadastrar->proprietarioId);

        if (!$stmt->execute()) {

            throw new Exception("Erro ao tentar-se cadastrar o veículo na base de dados.");
        }

        $veiculoCadastrar->veiculoId = $this->bancoDados->lastInsertId();
    }

    // buscar veiculo pelo renavam
    public function buscarVeiculoPeloRenavam($renavamVeiculo) {
        $stmt = $this->bancoDados->prepare("SELECT tb_veiculos.veiculo_id, tb_veiculos.marca, tb_veiculos.modelo,
        tb_veiculos.renavam, tb_veiculos.numero_chassi, tb_veiculos.placa, tb_veiculos.ano_fabricacao,
        tb_veiculos.ano_modelo, tb_veiculos.cor, tb_veiculos.tipo_combustivel, tb_categorias_veiculos.categoria_veiculo_id AS id_categoria_do_veiculo,
        tb_categorias_veiculos.nome_categoria, tb_proprietarios.proprietario_id AS id_proprietario_do_veiculo, tb_proprietarios.nome_completo
        FROM tb_veiculos, tb_proprietarios, tb_categorias_veiculos
        WHERE tb_veiculos.categoria_veiculo_id = tb_categorias_veiculos.categoria_veiculo_id
        AND tb_veiculos.proprietario_id = tb_proprietarios.proprietario_id
        AND tb_veiculos.renavam = :renavam");
        $stmt->bindValue(":renavam", $renavamVeiculo);
        $stmt->execute();
        $veiculoObj = $stmt->fetchObject();

        if (empty($veiculoObj)) {
            
            return null;
        }

        // buscar fotos dos veiculos
        $fotos = [];

        $stmt = $this->bancoDados->prepare("SELECT * FROM tb_fotos_veiculos WHERE veiculo_id = :veiculo_id");
        $stmt->bindValue(":veiculo_id", $veiculoObj->veiculo_id);
        $stmt->execute();
        $fotosVeiculoObjs = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (count($fotosVeiculoObjs) > 0) {

            foreach ($fotosVeiculoObjs as $fotoVeiculoObj) {
                $foto = new FotoVeiculo();

                $foto->fotoVeiculoId = $fotoVeiculoObj->foto_veiculo_id;
                $foto->fotoUrl = $fotoVeiculoObj->foto_url;
                $foto->veiculoId = $fotoVeiculoObj->veiculo_id;

                $fotos[] = $foto;
            }

        }

        $veiculo = new Veiculo();
        $veiculo->veiculoId = $veiculoObj->veiculo_id;
        $veiculo->marca = $veiculoObj->marca;
        $veiculo->modelo = $veiculoObj->modelo;
        $veiculo->cor = $veiculoObj->cor;
        $veiculo->placa = $veiculoObj->placa;
        $veiculo->numeroChassi = $veiculoObj->numero_chassi;
        $veiculo->renavam = $veiculoObj->renavam;
        $veiculo->anoModelo = $veiculoObj->ano_modelo;
        $veiculo->anoFabricacao = $veiculoObj->ano_fabricacao;
        $veiculo->tipoCombustivel = $veiculoObj->tipo_combustivel;

        $categoriaVeiculo = new CategoriaVeiculo();
        $categoriaVeiculo->categoriaId = $veiculoObj->categoria_veiculo_id;
        $categoriaVeiculo->nomeCategoria = $veiculoObj->nome_categoria;
        $veiculo->categoriaVeiculo = $categoriaVeiculo;

        $proprietario = new Proprietario();
        $proprietario->proprietarioId = $veiculoObj->proprietario_id;
        $proprietario->nomeCompleto = $veiculoObj->nome_completo;
        $veiculo->proprietario = $proprietario;

        return $veiculo;
    }

    public function buscarVeiculoPeloNumeroChassi($numeroChassiVeiculo) {
        
    }

    public function editar($veiculoEditar) {
        
    }

    // buscar veiculo pelo id
    public function buscarPeloId($idVeiculo) {
        $stmt = $this->bancoDados->prepare("SELECT tb_veiculos.veiculo_id, tb_veiculos.marca, tb_veiculos.modelo,
        tb_veiculos.renavam, tb_veiculos.numero_chassi, tb_veiculos.placa, tb_veiculos.ano_fabricacao,
        tb_veiculos.ano_modelo, tb_veiculos.cor, tb_veiculos.tipo_combustivel, tb_categorias_veiculos.categoria_veiculo_id AS id_categoria_do_veiculo,
        tb_categorias_veiculos.nome_categoria, tb_proprietarios.proprietario_id AS id_proprietario_do_veiculo, tb_proprietarios.nome_completo,
        tb_proprietarios.cpf, tb_proprietarios.rg, tb_proprietarios.telefone, tb_proprietarios.email,
        tb_proprietarios.data_nascimento, tb_proprietarios.numero_cnh
        FROM tb_veiculos, tb_proprietarios, tb_categorias_veiculos
        WHERE tb_veiculos.categoria_veiculo_id = tb_categorias_veiculos.categoria_veiculo_id
        AND tb_veiculos.proprietario_id = tb_proprietarios.proprietario_id
        AND tb_veiculos.veiculo_id = :veiculo_id");

        $stmt->bindValue(":veiculo_id", $idVeiculo);
        $stmt->execute();
        $veiculoObj = $stmt->fetchObject();

        if (empty($veiculoObj)) {

            return null;
        }

        $veiculo = new Veiculo();
        $veiculo->veiculoId = $veiculoObj->veiculo_id;
        $veiculo->marca = $veiculoObj->marca;
        $veiculo->modelo = $veiculoObj->modelo;
        $veiculo->renavam = $veiculoObj->renavam;
        $veiculo->placa = $veiculoObj->placa;
        $veiculo->numeroChassi = $veiculoObj->numero_chassi;
        $veiculo->anoFabricacao = $veiculoObj->ano_fabricacao;
        $veiculo->anoModelo = $veiculoObj->ano_modelo;
        $veiculo->cor = $veiculoObj->cor;
        $veiculo->tipoCombustivel = $veiculoObj->tipo_combustivel;
        $veiculo->categoriaVeiculoId = $veiculoObj->id_categoria_do_veiculo;
        $veiculo->proprietarioId = $veiculoObj->id_proprietario_do_veiculo;

        $categoriaVeiculo = new CategoriaVeiculo();
        $categoriaVeiculo->categoriaId = $veiculoObj->id_categoria_do_veiculo;
        $categoriaVeiculo->nomeCategoria = $veiculoObj->nome_categoria;

        $veiculo->categoriaVeiculo = $categoriaVeiculo;

        $proprietarioVeiculo = new Proprietario();
        $proprietarioVeiculo->proprietarioId = $veiculoObj->id_proprietario_do_veiculo;
        $proprietarioVeiculo->nomeCompleto = $veiculoObj->nome_completo;
        $proprietarioVeiculo->cpf = $veiculoObj->cpf;
        $proprietarioVeiculo->rg = $veiculoObj->rg;
        $proprietarioVeiculo->telefone = $veiculoObj->telefone;
        $proprietarioVeiculo->email = $veiculoObj->email;
        $proprietarioVeiculo->dataNascimento = new DateTime($veiculoObj->data_nascimento);
        $proprietarioVeiculo->numeroCnh = $veiculoObj->numero_cnh;

        $veiculo->proprietario = $proprietarioVeiculo;

        return $veiculo;
    }

    // buscar todos os veiculos
    public function buscarTodos($paginaAtual, $elementosPorPagina) {
        $stmt = $this->bancoDados->prepare("SELECT tb_veiculos.veiculo_id, tb_veiculos.marca, tb_veiculos.modelo,
        tb_veiculos.renavam, tb_veiculos.numero_chassi, tb_veiculos.placa, tb_veiculos.ano_fabricacao,
        tb_veiculos.ano_modelo, tb_veiculos.cor, tb_veiculos.tipo_combustivel, tb_categorias_veiculos.categoria_veiculo_id AS id_categoria_do_veiculo,
        tb_categorias_veiculos.nome_categoria, tb_proprietarios.proprietario_id AS id_proprietario_do_veiculo, tb_proprietarios.nome_completo,
        tb_proprietarios.cpf, tb_proprietarios.rg, tb_proprietarios.telefone, tb_proprietarios.email,
        tb_proprietarios.data_nascimento, tb_proprietarios.numero_cnh
        FROM tb_veiculos, tb_proprietarios, tb_categorias_veiculos
        WHERE tb_veiculos.categoria_veiculo_id = tb_categorias_veiculos.categoria_veiculo_id
        AND tb_veiculos.proprietario_id = tb_proprietarios.proprietario_id
        ORDER BY tb_veiculos.modelo ASC");

        $stmt->execute();
        $veiculosArray = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (empty($veiculosArray)) {

            return [];
        }

        $veiculos = [];
        
        foreach ($veiculosArray as $veiculoObj) {
            $veiculo = new Veiculo();
            $proprietario = new Proprietario();
            $categoria = new CategoriaVeiculo();

            $veiculo->veiculoId = $veiculoObj->veiculo_id;
            $veiculo->marca = $veiculoObj->marca;
            $veiculo->modelo = $veiculoObj->modelo;
            $veiculo->cor = $veiculoObj->cor;
            $veiculo->anoModelo = $veiculoObj->ano_modelo;
            $veiculo->anoFabricacao = $veiculoObj->ano_fabricacao;
            $veiculo->tipoCombustivel = $veiculoObj->tipo_combustivel;
            $veiculo->renavam = $veiculoObj->renavam;
            $veiculo->numeroChassi = $veiculoObj->numero_chassi;
            $veiculo->placa = $veiculoObj->placa;
            $veiculo->categoriaVeiculoId = $veiculoObj->id_categoria_do_veiculo;
            $veiculo->proprietarioId = $veiculoObj->id_proprietario_do_veiculo;

            $categoria->categoriaId = $veiculoObj->id_categoria_do_veiculo;
            $categoria->nomeCategoria = $veiculoObj->nome_categoria;

            $proprietario->proprietarioId = $veiculoObj->id_proprietario_do_veiculo;
            $proprietario->nomeCompleto = $veiculoObj->nome_completo;
            $proprietario->cpf = $veiculoObj->cpf;
            $proprietario->rg = $veiculoObj->rg;
            $proprietario->dataNascimento = $veiculoObj->data_nascimento;
            $proprietario->telefone = $veiculoObj->telefone;
            $proprietario->email = $veiculoObj->email;
            $proprietario->numeroCnh = $veiculoObj->numero_cnh;

            $veiculo->categoriaVeiculo = $categoria;
            $veiculo->proprietario = $proprietario;

            $veiculos[] = $veiculo;
        }

        return $veiculos;
    }

    public function registrarFotoVeiculo($fotoVeiculoUrl, $veiculoId) {
        
    }

    public function deletar($idVeiculoDeletar) {
        
    }

    public function deletarFotoVeiculo($fotoVeiculoIdDeletar) {
        
    }

    private function buscarFotosVeiculo() {

    }

}