<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrarTseAgu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        self::rodarScript1();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }

    public function rodarScript1(){


        DB::select( DB::raw("
            CREATE OR REPLACE FUNCTION fc_habilitar_triggers( nome_schema TEXT, habilitar BOOLEAN )
            RETURNS VOID AS $$ DECLARE tbl RECORD;
            BEGIN
                FOR tbl IN SELECT schemaname || '.' || tablename AS nome
                FROM pg_tables
                WHERE schemaname = nome_schema
                LOOP
                    IF ( habilitar = TRUE )
                    THEN RAISE NOTICE 'Habilitando Triggers da Tabela: %', tbl.nome; EXECUTE 'ALTER TABLE ' || tbl.nome || ' ENABLE TRIGGER ALL';
                    ELSE RAISE NOTICE 'Desabilitando Triggers da Tabela: %', tbl.nome; EXECUTE 'ALTER TABLE ' || tbl.nome || ' DISABLE TRIGGER ALL';
                    END IF;
                END LOOP;
                RETURN;
            END;
            $$ LANGUAGE 'plpgsql';
        ") );

        DB::select( DB::raw("
        SELECT fc_habilitar_triggers('public', FALSE );
        ") );

        DB::select( DB::raw("
        ALTER TABLE fornecedores DROP CONSTRAINT fornecedores_cpf_cnpj_idgener_unique;
        ") );


        DB::select( DB::raw("
        ALTER TABLE users DROP CONSTRAINT users_cpf_unique;
        ") );


        DB::select( DB::raw("
        ALTER TABLE users DROP CONSTRAINT users_email_unique;
        ") );


    }
}
