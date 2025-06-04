<?php

namespace Repositorio;

interface IProprietarioRepositorio {

    function cadastrar($proprietarioCadastrar);

    function cadastrarEndereco($enderecoProprietarioCadastrar);

    function editarEndereco($enderecoProprietarioEditar);

    function editar($proprietarioEditar);

    function buscarPeloId($idProprietarioConsultar);

    function atualizarFotoProprietario($fotoProprietario, $proprietarioId);

    function atualizarFotoFrenteDocumento($fotoFrenteDocumento, $proprietarioId);

    function atualizarFotoVersoDocumento($fotoVersoDocumento, $proprietarioId);

    function atualizarFotoComprovanteResidencia($fotoComprovanteResidencia, $proprietarioId);

    function buscarPeloEmail($emailProprietario);

    function buscarPeloCpf($cpfProprietario);

    function buscarPeloRg($rgProprietario);

}