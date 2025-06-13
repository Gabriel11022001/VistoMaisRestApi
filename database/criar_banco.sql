-- criar tabela de categoria de veiculo
create table if not exists tb_categorias_veiculos(
	categoria_veiculo_id serial primary key,
	nome_categoria text not null
);

-- criar tabela de proprietarios
create table if not exists tb_proprietarios(
	proprietario_id serial primary key,
	nome_completo text not null,
	telefone text not null,
	email text,
	cpf text not null,
	rg text not null,
	data_nascimento date not null,
	numero_cnh text,
	foto_proprietario text,
	foto_comprovante_residencia text,
	foto_frente_documento text,
	foto_verso_documento text
);

-- criar tabela de endereços
create table if not exists tb_enderecos(
	endereco_id serial primary key,
	cep text not null,
	logradouro text not null,
	complemento text,
	bairro text not null,
	cidade text not null,
	estado text not null,
	numero text,
	proprietario_id integer not null,
	foreign key(proprietario_id) references tb_proprietarios(proprietario_id)
);

-- criar tabela de veiculos
create table if not exists tb_veiculos(
	veiculo_id serial primary key,
	modelo text not null,
	marca text not null,
	cor text not null,
	ano_fabricacao integer not null,
	ano_modelo integer not null,
	placa text not null,
	numero_chassi text not null,
	renavam text not null,
	tipo_combustivel text not null,
	proprietario_id integer not null,
	categoria_veiculo_id integer not null,
	foreign key(proprietario_id) references tb_proprietarios(proprietario_id),
	foreign key(categoria_veiculo_id) references tb_categorias_veiculos(categoria_veiculo_id)
);

-- criar tabela das fotos dos veículos
create table if not exists tb_fotos_veiculos(
	foto_veiculo_id serial primary key,
	foto_url text not null,
	veiculo_id integer not null,
	foreign key(veiculo_id) references tb_veiculos(veiculo_id)
);