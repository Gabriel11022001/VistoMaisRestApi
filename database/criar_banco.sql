-- criar tabela de categoria de veiculo
create table if not exists tb_categorias_veiculos(
	categoria_veiculo_id serial primary key,
	nome_categoria text not null
);