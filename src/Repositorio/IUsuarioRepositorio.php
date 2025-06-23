<?php

namespace Repositorio;

interface IUsuarioRepositorio {

    function cadastrarUsuario($usuarioCadastrar);

    function editarUsuario($usuarioEditar);

    function buscarTodosUsuarios($paginaAtual, $elementosPorPagina);

    function buscarUsuarioPeloLoginSenha($login, $senha);

    function deletarUsuario($idUsuarioDeletar);

    function buscarUsuarioPeloLogin($login);

}