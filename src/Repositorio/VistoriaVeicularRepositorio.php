<?php

namespace Repositorio;

use DateTime;
use Exception;
use Models\CategoriaVeiculo;
use Models\Proprietario;
use Models\Veiculo;
use Models\VistoriaVeicular;
use PDO;

class VistoriaVeicularRepositorio extends Repositorio implements IVistoriaVeicularRepositorio {

    public function __construct(PDO $bancoDados)
    {
        parent::__construct($bancoDados);
    }

    // cadastrar veiculo
    public function cadastrarVistoriaVeicular($vistoriaVeicularCadastrar) {
        $stmt = $this->bancoDados->prepare("INSERT INTO tb_vistorias_veiculares(status, data_realizacao,
        veiculo_id, nome_vistoriador, pneus_nao_estao_desgastados, pressao_pneus_correto, farois_perfeito_funcionamento,
        lanternas_perfeito_funcionamento, setas_perfeito_funcionamento, sistema_freios_perfeito_funcionamento,
        motor_perfeito_funcionamento, cinto_seguranca_perfeito_funcionamento, possui_triangulo_sinalizacao,
        extintor_incendio_esta_data_validade, extintor_incendio_perfeito_funcionamento,
        observacoes, recomendacoes, classificacao_geral_veiculo) VALUES(:status, :data_realizacao,
        :veiculo_id, :nome_vistoriador, :pneus_nao_estao_desgastados, :pressao_pneus_correto, :farois_perfeito_funcionamento,
        :lanternas_perfeito_funcionamento, :setas_perfeito_funcionamento, :sistema_freios_perfeito_funcionamento,
        :motor_perfeito_funcionamento, :cinto_seguranca_perfeito_funcionamento, :possui_triangulo_sinalizacao,
        :extintor_incendio_esta_data_validade, :extintor_incendio_perfeito_funcionamento,
        :observacoes, :recomendacoes, :classificacao_geral_veiculo)");

        $stmt->bindValue(":status", $vistoriaVeicularCadastrar->status);
        $stmt->bindValue(":data_realizacao", $vistoriaVeicularCadastrar->dataRealizacao->format("Y-m-d"));
        $stmt->bindValue(":veiculo_id", $vistoriaVeicularCadastrar->veiculoId);
        $stmt->bindValue(":nome_vistoriador", $vistoriaVeicularCadastrar->nomeVistoriador);
        $stmt->bindValue(":pneus_nao_estao_desgastados", $vistoriaVeicularCadastrar->pneusNaoEstaoDesgastados, PDO::PARAM_BOOL);
        $stmt->bindValue(":pressao_pneus_correto", $vistoriaVeicularCadastrar->pressaoPneusCorreto, PDO::PARAM_BOOL);
        $stmt->bindValue(":farois_perfeito_funcionamento", $vistoriaVeicularCadastrar->faroisPerfeitoFuncionamento, PDO::PARAM_BOOL);
        $stmt->bindValue(":lanternas_perfeito_funcionamento", $vistoriaVeicularCadastrar->lanternasPerfeitoFuncionamento, PDO::PARAM_BOOL);
        $stmt->bindValue(":setas_perfeito_funcionamento", $vistoriaVeicularCadastrar->setasPerfeitoFuncionamento, PDO::PARAM_BOOL);
        $stmt->bindValue(":sistema_freios_perfeito_funcionamento", $vistoriaVeicularCadastrar->sistemaFreiosPerfeitoFuncionamento, PDO::PARAM_BOOL);
        $stmt->bindValue(":motor_perfeito_funcionamento", $vistoriaVeicularCadastrar->motorPerfeitoFuncionamento, PDO::PARAM_BOOL);
        $stmt->bindValue(":cinto_seguranca_perfeito_funcionamento", $vistoriaVeicularCadastrar->cintoSegurancaPerfeitoFuncionamento, PDO::PARAM_BOOL);
        $stmt->bindValue(":possui_triangulo_sinalizacao", $vistoriaVeicularCadastrar->possuiTrianguloSinalizacao, PDO::PARAM_BOOL);
        $stmt->bindValue(":extintor_incendio_esta_data_validade", $vistoriaVeicularCadastrar->extintorIncendioEstaDataValidade, PDO::PARAM_BOOL);
        $stmt->bindValue(":extintor_incendio_perfeito_funcionamento", $vistoriaVeicularCadastrar->extintorIncendioPerfeitoFuncionamento, PDO::PARAM_BOOL);
        $stmt->bindValue(":observacoes", $vistoriaVeicularCadastrar->observacoes);
        $stmt->bindValue(":recomendacoes", $vistoriaVeicularCadastrar->recomendacoes);
        $stmt->bindValue(":classificacao_geral_veiculo", $vistoriaVeicularCadastrar->classificacaoGeralVeiculo);

        $stmt->execute();

        $vistoriaVeicularCadastrar->vistoriaId = intval($this->bancoDados->lastInsertId());
    }

    public function editarVistoriaVeicular($vistoriaVeicularEditar) {
        
    }

    // deletar vistoria veicular
    public function deletarVistoriaVeicular($idVistoriaVeicular) {
        $stmt = $this->bancoDados->prepare("DELETE FROM tb_vistorias_veiculares WHERE vistoria_veicular_id = :vistoria_veicular_id");
        
        $stmt->bindValue(":vistoria_veicular_id", $idVistoriaVeicular);
        
        if (!$stmt->execute()) {

            throw new Exception("Erro ao tentar-se deletar a vistoria.");
        }

    }

    // buscar vistorias veiculares
    public function buscarVistoriasVeiculares($paginaAtual, $elementosPorPagina) {
        $stmt = $this->bancoDados->prepare("SELECT vi.*, vei.modelo, vei.marca, vei.cor, vei.ano_modelo,
        vei.ano_fabricacao, vei.placa, vei.renavam, vei.numero_chassi, vei.tipo_combustivel,
        pro.proprietario_id, pro.nome_completo, pro.cpf, pro.telefone, pro.email,
        pro.rg, pro.numero_cnh, pro.foto_proprietario, pro.foto_frente_documento,
        pro.foto_verso_documento, pro.foto_comprovante_residencia,
        pro.data_nascimento, cate.categoria_veiculo_id, cate.nome_categoria
        FROM tb_vistorias_veiculares AS vi, tb_veiculos AS vei, tb_proprietarios AS pro, tb_categorias_veiculos AS cate
        WHERE vi.veiculo_id = vei.veiculo_id
        AND vei.categoria_veiculo_id = cate.categoria_veiculo_id
        AND vei.proprietario_id = pro.proprietario_id");

        $stmt->execute();
        $vistoriasArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($vistoriasArray)) {

            return array();
        }

        $vistorias = array();

        foreach ($vistoriasArray as $vistoriaArray) {
            $vistoria = new VistoriaVeicular();
            $proprietario = new Proprietario();
            $categoriaVeiculo = new CategoriaVeiculo();
            $veiculo = new Veiculo();

            $vistoria->vistoriaId = $vistoriaArray["vistoria_veicular_id"];
            $vistoria->nomeVistoriador = $vistoriaArray["nome_vistoriador"];
            $vistoria->dataRealizacao = new DateTime($vistoriaArray["data_realizacao"]);
            $vistoria->pneusNaoEstaoDesgastados = $vistoriaArray["pneus_nao_estao_desgastados"];
            $vistoria->pressaoPneusCorreto = $vistoriaArray["pressao_pneus_correto"];
            $vistoria->faroisPerfeitoFuncionamento = $vistoriaArray["farois_perfeito_funcionamento"];
            $vistoria->lanternasPerfeitoFuncionamento = $vistoriaArray["lanternas_perfeito_funcionamento"];
            $vistoria->setasPerfeitoFuncionamento = $vistoriaArray["setas_perfeito_funcionamento"];
            $vistoria->sistemaFreiosPerfeitoFuncionamento = $vistoriaArray["sistema_freios_perfeito_funcionamento"];
            $vistoria->motorPerfeitoFuncionamento = $vistoriaArray["motor_perfeito_funcionamento"];
            $vistoria->cintoSegurancaPerfeitoFuncionamento = $vistoriaArray["cinto_seguranca_perfeito_funcionamento"];
            $vistoria->possuiTrianguloSinalizacao = $vistoriaArray["possui_triangulo_sinalizacao"];
            $vistoria->extintorIncendioEstaDataValidade = $vistoriaArray["extintor_incendio_esta_data_validade"];
            $vistoria->extintorIncendioPerfeitoFuncionamento = $vistoriaArray["extintor_incendio_perfeito_funcionamento"];
            $vistoria->observacoes = $vistoriaArray["observacoes"];
            $vistoria->recomendacoes = $vistoriaArray["recomendacoes"];
            $vistoria->classificacaoGeralVeiculo = $vistoriaArray["classificacao_geral_veiculo"];
            $vistoria->veiculoId = $vistoriaArray["veiculo_id"];
            $vistoria->status = $vistoriaArray["status"];

            $categoriaVeiculo->categoriaId = $vistoriaArray["categoria_veiculo_id"];
            $categoriaVeiculo->nomeCategoria = $vistoriaArray["nome_categoria"];
            $veiculo->categoriaVeiculo = $categoriaVeiculo;

            $veiculo->veiculoId = $vistoriaArray["veiculo_id"];
            $veiculo->modelo = $vistoriaArray["modelo"];
            $veiculo->marca = $vistoriaArray["marca"];
            $veiculo->anoModelo = $vistoriaArray["ano_modelo"];
            $veiculo->anoFabricacao = $vistoriaArray["ano_fabricacao"];
            $veiculo->cor = $vistoriaArray["cor"];
            $veiculo->placa = $vistoriaArray["placa"];
            $veiculo->numeroChassi = $vistoriaArray["numero_chassi"];
            $veiculo->renavam = $vistoriaArray["renavam"];
            $veiculo->tipoCombustivel = $vistoriaArray["tipo_combustivel"];

            $proprietario->proprietarioId = $vistoriaArray["proprietario_id"];
            $proprietario->nomeCompleto = $vistoriaArray["nome_completo"];
            $proprietario->cpf = $vistoriaArray["cpf"];
            $proprietario->rg = $vistoriaArray["rg"];
            $proprietario->telefone = $vistoriaArray["telefone"];
            $proprietario->email = $vistoriaArray["email"];
            $proprietario->dataNascimento = $vistoriaArray["data_nascimento"];
            $proprietario->numeroCnh = $vistoriaArray["numero_cnh"];

            $veiculo->proprietario = $proprietario;
            $veiculo->categoriaVeiculo = $categoriaVeiculo;
            $vistoria->veiculo = $veiculo;

            $vistorias[] = $vistoria;
        }

        return $vistorias;
    }

    // buscar vistoria de veiculo pelo id
    public function buscarVistoriaVeicularPeloId($idVistoriaVeicular) {
        $stmt = $this->bancoDados->prepare("SELECT vi.*, vei.modelo, vei.marca, vei.cor, vei.ano_modelo,
        vei.ano_fabricacao, vei.placa, vei.renavam, vei.numero_chassi, vei.tipo_combustivel,
        pro.proprietario_id, pro.nome_completo, pro.cpf, pro.telefone, pro.email,
        pro.rg, pro.numero_cnh, pro.foto_proprietario, pro.foto_frente_documento,
        pro.foto_verso_documento, pro.foto_comprovante_residencia,
        pro.data_nascimento, cate.categoria_veiculo_id, cate.nome_categoria
        FROM tb_vistorias_veiculares AS vi, tb_veiculos AS vei, tb_proprietarios AS pro, tb_categorias_veiculos AS cate
        WHERE vi.veiculo_id = vei.veiculo_id
        AND vei.categoria_veiculo_id = cate.categoria_veiculo_id
        AND vei.proprietario_id = pro.proprietario_id
        AND vi.vistoria_veicular_id = :vistoria_veicular_id");

        $stmt->bindValue(":vistoria_veicular_id", $idVistoriaVeicular);
        $stmt->execute();
        $vistoriaObj = $stmt->fetch(PDO::FETCH_OBJ);

        if (empty($vistoriaObj)) {

            return null;
        }

        $vistoria = new VistoriaVeicular();
        $proprietario = new Proprietario();
        $veiculo = new Veiculo();
        $categoriaVeiculo = new CategoriaVeiculo();

        $categoriaVeiculo->categoriaId = $vistoriaObj->categoria_veiculo_id;
        $categoriaVeiculo->nomeCategoria = $vistoriaObj->nome_categoria;

        $veiculo->veiculoId = $vistoriaObj->veiculo_id;
        $veiculo->categoriaVeiculo = $categoriaVeiculo;
        $veiculo->categoriaVeiculoId = $vistoriaObj->categoria_veiculo_id;
        $veiculo->marca = $vistoriaObj->marca;
        $veiculo->modelo = $vistoriaObj->modelo;
        $veiculo->cor = $vistoriaObj->cor;
        $veiculo->anoFabricacao = $vistoriaObj->ano_fabricacao;
        $veiculo->anoModelo = $vistoriaObj->ano_modelo;
        $veiculo->numeroChassi = $vistoriaObj->numero_chassi;
        $veiculo->placa = $vistoriaObj->placa;
        $veiculo->renavam = $vistoriaObj->renavam;
        $veiculo->tipoCombustivel = $vistoriaObj->tipo_combustivel;

        $proprietario->proprietarioId = $vistoriaObj->proprietario_id;
        $proprietario->nomeCompleto = $vistoriaObj->nome_completo;
        $proprietario->cpf = $vistoriaObj->cpf;
        $proprietario->rg = $vistoriaObj->rg;
        $proprietario->dataNascimento = new DateTime($vistoriaObj->data_nascimento);
        $proprietario->telefone = $vistoriaObj->telefone;
        $proprietario->email = $vistoriaObj->email;
        $proprietario->numeroCnh = $vistoriaObj->numero_cnh;

        $veiculo->proprietario = $proprietario;
        $veiculo->proprietarioId = $vistoriaObj->proprietario_id;

        $vistoria->veiculo = $veiculo;

        $vistoria->vistoriaId = $vistoriaObj->vistoria_veicular_id;
        $vistoria->nomeVistoriador = $vistoriaObj->nome_vistoriador;
        $vistoria->dataRealizacao = new DateTime($vistoriaObj->data_realizacao);
        $vistoria->pneusNaoEstaoDesgastados = $vistoriaObj->pneus_nao_estao_desgastados;
        $vistoria->pressaoPneusCorreto = $vistoriaObj->pressao_pneus_correto;
        $vistoria->faroisPerfeitoFuncionamento = $vistoriaObj->farois_perfeito_funcionamento;
        $vistoria->lanternasPerfeitoFuncionamento = $vistoriaObj->lanternas_perfeito_funcionamento;
        $vistoria->setasPerfeitoFuncionamento = $vistoriaObj->setas_perfeito_funcionamento;
        $vistoria->sistemaFreiosPerfeitoFuncionamento = $vistoriaObj->sistema_freios_perfeito_funcionamento;
        $vistoria->motorPerfeitoFuncionamento = $vistoriaObj->motor_perfeito_funcionamento;
        $vistoria->cintoSegurancaPerfeitoFuncionamento = $vistoriaObj->cinto_seguranca_perfeito_funcionamento;
        $vistoria->possuiTrianguloSinalizacao = $vistoriaObj->possui_triangulo_sinalizacao;
        $vistoria->extintorIncendioEstaDataValidade = $vistoriaObj->extintor_incendio_esta_data_validade;
        $vistoria->extintorIncendioPerfeitoFuncionamento = $vistoriaObj->extintor_incendio_perfeito_funcionamento;
        $vistoria->observacoes = $vistoriaObj->observacoes;
        $vistoria->recomendacoes = $vistoriaObj->recomendacoes;
        $vistoria->classificacaoGeralVeiculo = $vistoriaObj->classificacao_geral_veiculo;
        $vistoria->veiculoId = $vistoriaObj->veiculo_id;
        $vistoria->status = $vistoriaObj->status;

        return $vistoria;
    }

}