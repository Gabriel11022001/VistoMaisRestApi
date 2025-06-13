<?php

namespace Repositorio;

interface IVeiculoRepositorio {

    function cadastrar($veiculoCadastrar);

    function editar($veiculoEditar);

    function buscarTodos($paginaAtual, $elementosPorPagina);

    function buscarPeloId($idVeiculo);

    function deletar($idVeiculoDeletar);

    function registrarFotoVeiculo($fotoVeiculoUrl, $veiculoId);

    function deletarFotoVeiculo($fotoVeiculoIdDeletar);

    function buscarVeiculoPeloRenavam($renavamVeiculo);

    function buscarVeiculoPeloNumeroChassi($numeroChassiVeiculo);

}