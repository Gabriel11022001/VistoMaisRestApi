<?php

namespace Validators;

class ValidaCamposCadastroVeiculo {

    private static $camposObrigatorios = array(
        "modelo",
        "marca",
        "ano_lancamento",
        "ano_modelo",
        "cor",
        "renavam",
        "numero_chassi",
        "placa",
        "tipo_combustivel",
        "categoria_veiculo_id",
        "proprietario_id"
    );

    // validar campos do veículo no cadastro
    public static function validar($camposCadastroVeiculo = array()) {
        $erros = [];

        if (empty($camposCadastroVeiculo["modelo"]) && in_array("modelo", self::$camposObrigatorios)) {
            $erros["modelo"] = "Informe o modelo do veiculo.";
        }

        if (empty($camposCadastroVeiculo["marca"]) && in_array("marca", self::$camposObrigatorios)) {
            $erros["marca"] = "Informe a marca.";
        }

        if (empty($camposCadastroVeiculo["ano_lancamento"]) && in_array("ano_lancamento", self::$camposObrigatorios)) {
            $erros["ano_lancamento"] = "Informe o ano de lançamento.";
        }

        if (empty($camposCadastroVeiculo["ano_modelo"]) && in_array("ano_modelo", self::$camposObrigatorios)) {
            $erros["ano_modelo"] = "Informe o ano de modelo.";
        }

        if (empty($camposCadastroVeiculo["renavam"]) && in_array("renavam", self::$camposObrigatorios)) {
            $erros["renavam"] = "Informe o renavam.";
        }

        if (empty($camposCadastroVeiculo["placa"]) && in_array("placa", self::$camposObrigatorios)) {
            $erros["placa"] = "Informe a placa do veiculo.";
        }

        if (empty($camposCadastroVeiculo["numero_chassi"]) && in_array("numero_chassi", self::$camposObrigatorios)) {
            $erros["numero_chassi"] = "Informe o numero do chassi.";
        }

        if (empty($camposCadastroVeiculo["tipo_combustivel"]) && in_array("tipo_combustivel", self::$camposObrigatorios)) {
            $erros["tipo_combustivel"] = "Informe o tipo de combustivel.";
        }

        if (empty($camposCadastroVeiculo["categoria_veiculo_id"]) && in_array("categoria_veiculo_id", self::$camposObrigatorios)) {
            $erros["categoria_veiculo_id"] = "Informe o id da categoria do veiculo.";
        }

        if (empty($camposCadastroVeiculo["proprietario_id"]) && in_array("proprietario_id", self::$camposObrigatorios)) {
            $erros["proprietario_id"] = "Informe o id do proprietário.";
        }

        return $erros;
    }

}