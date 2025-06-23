<?php

namespace Repositorio;

use Exception;
use PDO;

class TokenRepositorio extends Repositorio {

    public function __construct(PDO $bancoDados)
    {
        parent::__construct($bancoDados);
    }

    // cadastrar um novo token para o usuário
    public function registrarTokenUsuario($tokenUsuarioCadastrar) {
        $stmt = $this->bancoDados->prepare("INSERT INTO tb_tokens(token, data_cadastro, data_limite, usuario_id)
        VALUES(:token, :data_cadastro, :data_limite, :usuario_id)");

        $stmt->bindValue(":token", $tokenUsuarioCadastrar->token);
        $stmt->bindValue(":data_cadastro", $tokenUsuarioCadastrar->dataCadastro);
        $stmt->bindValue(":data_limite", $tokenUsuarioCadastrar->dataLimite);
        $stmt->bindValue(":usuario_id", $tokenUsuarioCadastrar->usuario_id);

        if (!$stmt->execute()) {

            throw new Exception("Erro ao tentar-se cadastrar o token do usuário.");
        }

        $tokenUsuarioCadastrar->tokenId = $this->bancoDados->lastInsertId();
    }

    // deletar todos os tokens do usuário
    public function deletarTodosTokensUsuario($usuarioId) {
        $stmt = $this->bancoDados->prepare("DELETE FROM tb_tokens WHERE usuario_id = :usuario_id");
        $stmt->bindValue(":usuario_id", $usuarioId);

        if (!$stmt->execute()) {

            throw new Exception("Erro ao tentar-se deletar os tokens do usuário.");
        }

    }

}