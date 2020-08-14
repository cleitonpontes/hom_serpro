-- remover constraints
ALTER TABLE fornecedores DROP CONSTRAINT fornecedores_cpf_cnpj_idgener_unique;
ALTER TABLE users DROP CONSTRAINT users_cpf_unique;
ALTER TABLE users DROP CONSTRAINT users_email_unique;
