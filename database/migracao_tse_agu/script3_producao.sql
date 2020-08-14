-- rodar após script php
delete from fornecedores where deleted_at is not null;
delete from users where deleted_at is not null;
delete from codigos where deleted_at is not null;

ALTER TABLE fornecedores ADD CONSTRAINT fornecedores_cpf_cnpj_idgener_unique UNIQUE (cpf_cnpj_idgener);
ALTER TABLE users ADD CONSTRAINT users_cpf_unique UNIQUE (cpf);
ALTER TABLE users ADD CONSTRAINT users_email_unique UNIQUE (email);
