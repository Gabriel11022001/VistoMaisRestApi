<?php

namespace Validators;

class ValidaCamposCadastroProprietario {

    // validar campos no cadastro de proprietário
    public static function validar($campos = []) {
        $errosCampos = array();

        if (empty($campos["nome_completo"])) {
            $errosCampos["nome_completo"] = "Informe o nome completo.";
        } else if (strlen($campos["nome_completo"]) < 3) {
            $errosCampos["nome_completo"] = "O nome completo deve possuir no minino três caracteres.";
        }
        
        if (empty($campos["cpf"])) {
            $errosCampos["cpf"] = "Informe o cpf.";
        }

        if (empty($campos["email"])) {
            $errosCampos["email"] = "Informe o e-mail.";
        }

        if (empty($campos["telefone"])) {
            $errosCampos["telefone"] = "Informe o telefone.";
        }

        if (empty($campos["rg"])) {
            $errosCampos["rg"] = "Informe o rg.";
        }

        if (empty($campos["data_nascimento"])) {
            $errosCampos["data_nascimento"] = "Informe a data de nascimento.";
        }

        if (!empty($campos["numero_cnh"])) {
            // validar o número da cnh
        }

        if (empty($campos["endereco"]["cep"])) {
            $errosCampos["endereco"] = "Informe o cep.";
        }

        if (empty($campos["endereco"]["logradouro"])) {
            $errosCampos["logradouro"] = "Informe o logradouro.";
        }

        if (empty($campos["endereco"]["bairro"])) {
            $errosCampos["bairro"] = "Informe o bairro.";
        }

        if (empty($campos["endereco"]["cidade"])) {
            $errosCampos["cidade"] = "Informe a cidade.";
        }

        if (empty($campos["endereco"]["estado"])) {
            $errosCampos["estado"] = "Informe o estado.";
        } else {
            // validar se o estado(unidade federativa) é válido
        }

        if (!empty($campos["endereco"]["numero"])) {

            if (is_int($campos["endereco"]["numero"]) && $campos["endereco"]["numero"] < 0) {
                $errosCampos["numero"] = "Número residencial inválido.";
            } else if ($campos["endereco"]["numero"] != "s/n") {
                $errosCampos["numero"] = "Número residencial inválido.";
            }

        }

        return $errosCampos;
    }

}