-- criar a função para desabilitar trigger
CREATE OR REPLACE FUNCTION fc_habilitar_triggers( nome_schema TEXT, habilitar BOOLEAN ) RETURNS VOID AS $BODY$ DECLARE tbl RECORD; BEGIN FOR tbl IN SELECT schemaname || '.' || tablename AS nome FROM pg_tables WHERE schemaname = nome_schema LOOP IF ( habilitar = TRUE ) THEN RAISE NOTICE 'Habilitando Triggers da Tabela: %', tbl.nome; EXECUTE 'ALTER TABLE ' || tbl.nome || ' ENABLE TRIGGER ALL'; ELSE RAISE NOTICE 'Desabilitando Triggers da Tabela: %', tbl.nome; EXECUTE 'ALTER TABLE ' || tbl.nome || ' DISABLE TRIGGER ALL'; END IF; END LOOP; RETURN; END; $BODY$ LANGUAGE 'plpgsql';
-- desabilitar trigger
SELECT fc_habilitar_triggers('public', FALSE );
-- remover constraints
ALTER TABLE fornecedores DROP CONSTRAINT fornecedores_cpf_cnpj_idgener_unique;
ALTER TABLE users DROP CONSTRAINT users_cpf_unique;
ALTER TABLE users DROP CONSTRAINT users_email_unique;
