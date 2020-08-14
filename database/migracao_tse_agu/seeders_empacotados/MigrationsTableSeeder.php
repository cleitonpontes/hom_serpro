<?php

use Illuminate\Database\Seeder;

class MigrationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {



        \DB::table('migrations')->insert(array (
            0 =>
            array (
                'id' => 55000001,
                'migration' => '2013_11_29_174300_create_orgaossuperiores_table',
                'batch' => 1,
            ),
            1 =>
            array (
                'id' => 55000002,
                'migration' => '2013_11_29_174316_create_orgaos_table',
                'batch' => 1,
            ),
            2 =>
            array (
                'id' => 55000003,
                'migration' => '2013_11_29_174335_create_unidades_table',
                'batch' => 1,
            ),
            3 =>
            array (
                'id' => 55000004,
                'migration' => '2014_10_12_000000_create_users_table',
                'batch' => 1,
            ),
            4 =>
            array (
                'id' => 55000005,
                'migration' => '2014_10_12_100000_create_password_resets_table',
                'batch' => 1,
            ),
            5 =>
            array (
                'id' => 55000006,
                'migration' => '2016_05_10_130540_create_permission_tables',
                'batch' => 1,
            ),
            6 =>
            array (
                'id' => 55000007,
                'migration' => '2018_05_10_192735_create_sfpadrao_table',
                'batch' => 1,
            ),
            7 =>
            array (
                'id' => 55000008,
                'migration' => '2018_05_10_193603_create_sfdadosbasicos_table',
                'batch' => 1,
            ),
            8 =>
            array (
                'id' => 55000009,
                'migration' => '2018_05_10_200421_create_sfdocorigem_table',
                'batch' => 1,
            ),
            9 =>
            array (
                'id' => 55000010,
                'migration' => '2018_05_10_200435_create_sfpco_table',
                'batch' => 1,
            ),
            10 =>
            array (
                'id' => 55000011,
                'migration' => '2018_05_10_200449_create_sfpcoitem_table',
                'batch' => 1,
            ),
            11 =>
            array (
                'id' => 55000012,
                'migration' => '2018_05_10_200516_create_sfpso_table',
                'batch' => 1,
            ),
            12 =>
            array (
                'id' => 55000013,
                'migration' => '2018_05_10_200528_create_sfpsoitem_table',
                'batch' => 1,
            ),
            13 =>
            array (
                'id' => 55000014,
                'migration' => '2018_05_10_200552_create_sfdeducao_encargo_dadospagto_table',
                'batch' => 1,
            ),
            14 =>
            array (
                'id' => 55000015,
                'migration' => '2018_05_10_200614_create_sfitemrecolhimento_table',
                'batch' => 1,
            ),
            15 =>
            array (
                'id' => 55000016,
                'migration' => '2018_05_10_200629_create_sfacrescimo_table',
                'batch' => 1,
            ),
            16 =>
            array (
                'id' => 55000017,
                'migration' => '2018_05_10_200642_create_sfpredoc_table',
                'batch' => 1,
            ),
            17 =>
            array (
                'id' => 55000018,
                'migration' => '2018_05_10_200656_create_sfdomiciliobancario_table',
                'batch' => 1,
            ),
            18 =>
            array (
                'id' => 55000019,
                'migration' => '2018_05_10_200713_create_sfrelitemded_table',
                'batch' => 1,
            ),
            19 =>
            array (
                'id' => 55000020,
                'migration' => '2018_05_10_200726_create_sfdespesaanular_table',
                'batch' => 1,
            ),
            20 =>
            array (
                'id' => 55000021,
                'migration' => '2018_05_10_200741_create_sfdespesaanularitem_table',
                'batch' => 1,
            ),
            21 =>
            array (
                'id' => 55000022,
                'migration' => '2018_05_10_200755_create_sfcentrocusto_table',
                'batch' => 1,
            ),
            22 =>
            array (
                'id' => 55000023,
                'migration' => '2018_05_10_200809_create_sfrelitemvlrcc_table',
                'batch' => 1,
            ),
            23 =>
            array (
                'id' => 55000024,
                'migration' => '2018_05_10_200822_create_sfoutroslanc_table',
                'batch' => 1,
            ),
            24 =>
            array (
                'id' => 55000025,
                'migration' => '2018_05_10_200846_create_sfnonce_table',
                'batch' => 1,
            ),
            25 =>
            array (
                'id' => 55000026,
                'migration' => '2018_05_22_174235_create_rhddp_table',
                'batch' => 1,
            ),
            26 =>
            array (
                'id' => 55000027,
                'migration' => '2018_05_22_174250_create_rhddpdetalhado_table',
                'batch' => 1,
            ),
            27 =>
            array (
                'id' => 55000028,
                'migration' => '2018_05_23_191519_create_rhrubrica_table',
                'batch' => 1,
            ),
            28 =>
            array (
                'id' => 55000029,
                'migration' => '2018_06_19_130724_create_sfcertificado_table',
                'batch' => 1,
            ),
            29 =>
            array (
                'id' => 55000030,
                'migration' => '2018_07_30_161337_create_execsiafisituacao_table',
                'batch' => 1,
            ),
            30 =>
            array (
                'id' => 55000031,
                'migration' => '2018_07_30_190620_create_rhsituacao_table',
                'batch' => 1,
            ),
            31 =>
            array (
                'id' => 55000032,
                'migration' => '2018_07_30_190642_create_rhsituacao_rhrubrica_table',
                'batch' => 1,
            ),
            32 =>
            array (
                'id' => 55000033,
                'migration' => '2018_10_04_200351_create_apropriacoes_table',
                'batch' => 1,
            ),
            33 =>
            array (
                'id' => 55000034,
                'migration' => '2018_10_05_112832_create_apropriacoes_fases_table',
                'batch' => 1,
            ),
            34 =>
            array (
                'id' => 55000035,
                'migration' => '2018_11_01_150421_create_apropriacoes_importacao_table',
                'batch' => 1,
            ),
            35 =>
            array (
                'id' => 55000036,
                'migration' => '2018_11_05_163414_alter_execsiafisituacao_table',
                'batch' => 1,
            ),
            36 =>
            array (
                'id' => 55000037,
                'migration' => '2018_11_06_171633_create_situacoes_view',
                'batch' => 1,
            ),
            37 =>
            array (
                'id' => 55000038,
                'migration' => '2018_11_21_180726_create_apropriacoes_situacao_table',
                'batch' => 1,
            ),
            38 =>
            array (
                'id' => 55000039,
                'migration' => '2018_11_22_081218_create_apropriacoes_nota_empenho_table',
                'batch' => 1,
            ),
            39 =>
            array (
                'id' => 55000040,
                'migration' => '2018_11_26_130703_create_notifications_table',
                'batch' => 1,
            ),
            40 =>
            array (
                'id' => 55000041,
                'migration' => '2018_11_26_165752_create_activity_log_table',
                'batch' => 1,
            ),
            41 =>
            array (
                'id' => 55000042,
                'migration' => '2018_11_29_174357_create_calendarevents_table',
                'batch' => 1,
            ),
            42 =>
            array (
                'id' => 55000043,
                'migration' => '2018_11_29_175420_create_unidadesusers_table',
                'batch' => 1,
            ),
            43 =>
            array (
                'id' => 55000044,
                'migration' => '2018_12_02_213158_create_codigo_table',
                'batch' => 1,
            ),
            44 =>
            array (
                'id' => 55000045,
                'migration' => '2018_12_02_213223_create_codigoitem_table',
                'batch' => 1,
            ),
            45 =>
            array (
                'id' => 55000046,
                'migration' => '2018_12_02_214255_create_fornecedor_table',
                'batch' => 1,
            ),
            46 =>
            array (
                'id' => 55000047,
                'migration' => '2018_12_02_214357_create_contrato_table',
                'batch' => 1,
            ),
            47 =>
            array (
                'id' => 55000048,
                'migration' => '2018_12_02_214423_create_instalacao_table',
                'batch' => 1,
            ),
            48 =>
            array (
                'id' => 55000049,
                'migration' => '2018_12_02_214436_create_contratoresponsavel_table',
                'batch' => 1,
            ),
            49 =>
            array (
                'id' => 55000050,
                'migration' => '2018_12_02_214453_create_contratohistorico_table',
                'batch' => 1,
            ),
            50 =>
            array (
                'id' => 55000051,
                'migration' => '2018_12_02_214531_create_contratoocorrencia_table',
                'batch' => 1,
            ),
            51 =>
            array (
                'id' => 55000052,
                'migration' => '2018_12_02_214554_create_contratoterceirizado_table',
                'batch' => 1,
            ),
            52 =>
            array (
                'id' => 55000053,
                'migration' => '2018_12_02_221419_create_contratogarantia_table',
                'batch' => 1,
            ),
            53 =>
            array (
                'id' => 55000054,
                'migration' => '2018_12_05_124325_create_sfrelitemdespanular',
                'batch' => 1,
            ),
            54 =>
            array (
                'id' => 55000055,
                'migration' => '2018_12_18_152235_create_jobs_table',
                'batch' => 1,
            ),
            55 =>
            array (
                'id' => 55000056,
                'migration' => '2018_12_26_125537_create_planointerno_table',
                'batch' => 1,
            ),
            56 =>
            array (
                'id' => 55000057,
                'migration' => '2018_12_26_125625_create_naturezadespesa_table',
                'batch' => 1,
            ),
            57 =>
            array (
                'id' => 55000058,
                'migration' => '2018_12_26_125726_create_naturezasubitem_table',
                'batch' => 1,
            ),
            58 =>
            array (
                'id' => 55000059,
                'migration' => '2018_12_26_125803_create_empenhos_table',
                'batch' => 1,
            ),
            59 =>
            array (
                'id' => 55000060,
                'migration' => '2018_12_26_125848_create_empenhodetalhado_table',
                'batch' => 1,
            ),
            60 =>
            array (
                'id' => 55000061,
                'migration' => '2018_12_26_130000_create_documentosiafi_table',
                'batch' => 1,
            ),
            61 =>
            array (
                'id' => 55000062,
                'migration' => '2018_12_26_134248_create_tipolistafatura_table',
                'batch' => 1,
            ),
            62 =>
            array (
                'id' => 55000063,
                'migration' => '2018_12_26_134302_create_justificativafatura_table',
                'batch' => 1,
            ),
            63 =>
            array (
                'id' => 55000064,
                'migration' => '2018_12_26_134309_create_contratofaturas_table',
                'batch' => 1,
            ),
            64 =>
            array (
                'id' => 55000065,
                'migration' => '2019_01_11_170140_create_contrato_arquivos_table',
                'batch' => 1,
            ),
            65 =>
            array (
                'id' => 55000066,
                'migration' => '2019_01_31_204943_create_atualiza_saldos_function',
                'batch' => 1,
            ),
            66 =>
            array (
                'id' => 55000067,
                'migration' => '2019_01_31_210443_create_executa_atualiza_saldo_trigger',
                'batch' => 1,
            ),
            67 =>
            array (
                'id' => 55000068,
                'migration' => '2019_02_15_132103_alter_rhsituacao_table',
                'batch' => 1,
            ),
            68 =>
            array (
                'id' => 55000069,
                'migration' => '2019_02_27_202028_alter_sfrelitemdespanular_table',
                'batch' => 1,
            ),
            69 =>
            array (
                'id' => 55000070,
                'migration' => '2019_02_28_132335_create_contratoempenhos_table',
                'batch' => 1,
            ),
            70 =>
            array (
                'id' => 55000071,
                'migration' => '2019_03_11_114703_alter_contratofaturas_table',
                'batch' => 1,
            ),
            71 =>
            array (
                'id' => 55000072,
                'migration' => '2019_03_14_191943_create_centrocusto_table',
                'batch' => 1,
            ),
            72 =>
            array (
                'id' => 55000073,
                'migration' => '2019_03_14_200559_create_contratofatura_empenhos_table',
                'batch' => 1,
            ),
            73 =>
            array (
                'id' => 55000074,
                'migration' => '2019_05_14_125103_create_failed_jobs_table',
                'batch' => 1,
            ),
            74 =>
            array (
                'id' => 55000075,
                'migration' => '2019_06_06_135918_alter_contrato_table',
                'batch' => 1,
            ),
            75 =>
            array (
                'id' => 55000076,
                'migration' => '2019_06_06_140426_alter_contratohistorico_table',
                'batch' => 1,
            ),
            76 =>
            array (
                'id' => 55000077,
                'migration' => '2019_06_06_145844_create_contratocronograma_table',
                'batch' => 1,
            ),
            77 =>
            array (
                'id' => 55000078,
                'migration' => '2019_06_18_140433_alter_contratoresponsavel_table',
                'batch' => 1,
            ),
            78 =>
            array (
                'id' => 55000079,
                'migration' => '2019_06_19_164646_alter2_contratohistorico_table',
                'batch' => 1,
            ),
            79 =>
            array (
                'id' => 55000080,
                'migration' => '2019_06_21_163339_alter_contratocronograma_table',
                'batch' => 1,
            ),
            80 =>
            array (
                'id' => 55000081,
                'migration' => '2019_06_26_142540_create_catmatsergrupos_table',
                'batch' => 1,
            ),
            81 =>
            array (
                'id' => 55000082,
                'migration' => '2019_06_26_142604_create_catmatseritens_table',
                'batch' => 1,
            ),
            82 =>
            array (
                'id' => 55000083,
                'migration' => '2019_06_26_142605_create_contratoitem_table',
                'batch' => 1,
            ),
            83 =>
            array (
                'id' => 55000084,
                'migration' => '2019_06_26_150107_create_contratoitemsaldos_table',
                'batch' => 1,
            ),
            84 =>
            array (
                'id' => 55000085,
                'migration' => '2019_06_26_172839_create_catmatseratualizacao_table',
                'batch' => 1,
            ),
            85 =>
            array (
                'id' => 55000086,
                'migration' => '2019_06_30_183602_create_comunica_table',
                'batch' => 1,
            ),
            86 =>
            array (
                'id' => 55000087,
                'migration' => '2019_07_05_161008_alter2_execsfsituacao_table',
                'batch' => 1,
            ),
            87 =>
            array (
                'id' => 55000088,
                'migration' => '2019_07_15_160600_alter_empenhodetalhado_add_contas_table',
                'batch' => 1,
            ),
            88 =>
            array (
                'id' => 55000089,
                'migration' => '2019_07_15_160623_alter_atualiza_saldo_function',
                'batch' => 1,
            ),
            89 =>
            array (
                'id' => 55000090,
                'migration' => '2019_07_30_135959_alter_sfcertificado_table',
                'batch' => 1,
            ),
            90 =>
            array (
                'id' => 55000091,
                'migration' => '2019_07_30_183341_insert_roles_table',
                'batch' => 1,
            ),
            91 =>
            array (
                'id' => 55000092,
                'migration' => '2019_08_15_160906_dadotipocontrato_codigoitem_table',
                'batch' => 1,
            ),
            92 =>
            array (
                'id' => 55000093,
                'migration' => '2019_08_30_175211_insertpermision_configuracaounidade_dados',
                'batch' => 1,
            ),
            93 =>
            array (
                'id' => 55000094,
                'migration' => '2019_08_30_181742_create_unidadeconfiguracao_table',
                'batch' => 1,
            ),
            94 =>
            array (
                'id' => 55000095,
                'migration' => '2019_09_12_155345_alter_contratoterceirizado_create_descricaocomplementar',
                'batch' => 1,
            ),
            95 =>
            array (
                'id' => 55000096,
                'migration' => '2019_09_12_161338_alter_contratoarquivo_create_dadosprocesso',
                'batch' => 1,
            ),
            96 =>
            array (
                'id' => 55000097,
                'migration' => '2019_09_12_164601_create_orgaosubcategorias_table',
                'batch' => 1,
            ),
            97 =>
            array (
                'id' => 55000098,
                'migration' => '2019_09_12_165325_insertpermission_orgaosubcategorias_dados',
                'batch' => 1,
            ),
            98 =>
            array (
                'id' => 55000099,
                'migration' => '2019_09_12_185130_alter_contratos_subcategoria_id',
                'batch' => 1,
            ),
            99 =>
            array (
                'id' => 55000100,
                'migration' => '2019_09_12_195400_alter_contratoshistorico_subcategoria_id',
                'batch' => 1,
            ),
            100 =>
            array (
                'id' => 55000101,
                'migration' => '2019_09_19_114135_create_contratopreposto_table',
                'batch' => 1,
            ),
            101 =>
            array (
                'id' => 55000102,
                'migration' => '2019_09_19_120811_insertpermission_contratopreposto_dados',
                'batch' => 1,
            ),
            102 =>
            array (
                'id' => 55000103,
                'migration' => '2019_09_20_143936_create_app_version_table',
                'batch' => 1,
            ),
            103 =>
            array (
                'id' => 55000104,
                'migration' => '2019_09_20_144140_insert_app_version_5_0_000_dado',
                'batch' => 1,
            ),
            104 =>
            array (
                'id' => 55000105,
                'migration' => '2019_09_20_192247_create_unidades_requisitantes_colunm_contratos_table',
                'batch' => 1,
            ),
            105 =>
            array (
                'id' => 55000106,
                'migration' => '2019_10_04_202530_alter_contratocronograma_cria_campo_soma_subtrai',
                'batch' => 1,
            ),
            106 =>
            array (
                'id' => 55000107,
                'migration' => '2019_10_04_202611_alter_contratohistorico_cria_campo_soma_subtrai',
                'batch' => 1,
            ),
            107 =>
            array (
                'id' => 55000108,
                'migration' => '2019_10_07_121535_create_migracao_atualizacao_empenhos_permission',
                'batch' => 1,
            ),
            108 =>
            array (
                'id' => 55000109,
                'migration' => '2019_10_10_130620_create_rotina_alerta_mensal_permission',
                'batch' => 1,
            ),
            109 =>
            array (
                'id' => 55000110,
                'migration' => '2019_10_14_112436_insert_app_version_5_0_001_dado',
                'batch' => 1,
            ),
            110 =>
            array (
                'id' => 55000111,
                'migration' => '2019_10_17_142232_insert_app_version_5_0_002',
                'batch' => 1,
            ),
            111 =>
            array (
                'id' => 55000112,
                'migration' => '2019_10_24_143405_insert_app_version_5_0_003',
                'batch' => 2,
            ),
            112 =>
            array (
                'id' => 55000113,
                'migration' => '2019_10_30_142252_insert_app_version_5_0_004',
                'batch' => 2,
            ),
            113 =>
            array (
                'id' => 55000114,
                'migration' => '2019_10_31_103804_add_situacao_column_contratohistorico',
                'batch' => 2,
            ),
            114 =>
            array (
                'id' => 55000115,
                'migration' => '2019_10_31_110503_insert_app_version_5_0_005',
                'batch' => 2,
            ),
            115 =>
            array (
                'id' => 55000116,
                'migration' => '2019_12_03_113806_insert_app_version_5_0_006',
                'batch' => 3,
            ),
            116 =>
            array (
                'id' => 55000117,
                'migration' => '2019_12_12_130958_insert_app_version_5_0_007',
                'batch' => 3,
            ),
            117 =>
            array (
                'id' => 55000118,
                'migration' => '2020_01_08_192321_insert_app_version_5_0_008',
                'batch' => 3,
            ),
            118 =>
            array (
                'id' => 55000119,
                'migration' => '2020_01_14_194926_insert_app_version_5_0_9',
                'batch' => 3,
            ),
            119 =>
            array (
                'id' => 55000120,
                'migration' => '2020_01_15_141751_insert_app_version_5_0_10',
                'batch' => 3,
            ),
            120 =>
            array (
                'id' => 55000121,
                'migration' => '2020_01_23_204029_insert_app_version_5_0_11',
                'batch' => 3,
            ),
            121 =>
            array (
                'id' => 55000122,
                'migration' => '2020_01_29_181514_insert_app_version_5_0_12',
                'batch' => 3,
            ),
            122 =>
            array (
                'id' => 55000123,
                'migration' => '2020_02_12_211925_insert_app_version_5_0_13',
                'batch' => 3,
            ),
            123 =>
            array (
                'id' => 55000124,
                'migration' => '2020_02_13_221857_insert_app_version_5_0_14',
                'batch' => 3,
            ),
            124 =>
            array (
                'id' => 55000125,
                'migration' => '2020_02_14_124020_insert_tiposaldos_codigoitens_dados',
                'batch' => 3,
            ),
            125 =>
            array (
                'id' => 55000126,
                'migration' => '2020_02_14_130249_delete_contratoitemsaldos_table',
                'batch' => 3,
            ),
            126 =>
            array (
                'id' => 55000127,
                'migration' => '2020_02_14_133542_create_saldohistoricoitens_table',
                'batch' => 3,
            ),
            127 =>
            array (
                'id' => 55000128,
                'migration' => '2020_02_19_154052_insert_saldohistoricoitens_roles_dados',
                'batch' => 3,
            ),
            128 =>
            array (
                'id' => 55000129,
                'migration' => '2020_02_26_202634_insert_app_version_5_0_15',
                'batch' => 3,
            ),
            129 =>
            array (
                'id' => 55000130,
                'migration' => '2020_03_03_120124_create_orgaoconfiguracao_table',
                'batch' => 3,
            ),
            130 =>
            array (
                'id' => 55000131,
                'migration' => '2020_03_05_144052_add_orgao_to_comunica',
                'batch' => 3,
            ),
            131 =>
            array (
                'id' => 55000132,
                'migration' => '2020_03_14_214905_create_migracaosistemaconta_table',
                'batch' => 3,
            ),
            132 =>
            array (
                'id' => 55000133,
                'migration' => '2020_03_14_221542_insert_app_version_5_0_16',
                'batch' => 3,
            ),
            133 =>
            array (
                'id' => 55000134,
                'migration' => '2020_03_18_202938_insert_app_version_5_0_17',
                'batch' => 3,
            ),
            134 =>
            array (
                'id' => 55000135,
                'migration' => '2020_03_20_123200_insert_app_version_5_0_18',
                'batch' => 3,
            ),
            135 =>
            array (
                'id' => 55000136,
                'migration' => '2020_03_21_210842_create_subrogacoes_table',
                'batch' => 3,
            ),
            136 =>
            array (
                'id' => 55000137,
                'migration' => '2020_03_21_213031_insert_subrogacao_permissions_dados',
                'batch' => 3,
            ),
            137 =>
            array (
                'id' => 55000138,
                'migration' => '2020_03_22_010647_insert_app_version_5_0_19',
                'batch' => 3,
            ),
            138 =>
            array (
                'id' => 55000139,
                'migration' => '2020_03_26_162900_add_rp_to_empenhos',
                'batch' => 3,
            ),
            139 =>
            array (
                'id' => 55000140,
                'migration' => '2020_03_26_165607_insert_app_version_5_0_20',
                'batch' => 3,
            ),
            140 =>
            array (
                'id' => 55000141,
                'migration' => '2020_04_06_193100_insert_app_version_5_0_21',
                'batch' => 3,
            ),
            141 =>
            array (
                'id' => 55000142,
                'migration' => '2020_04_09_174337_insert_app_version_5_0_22',
                'batch' => 3,
            ),
            142 =>
            array (
                'id' => 55000143,
                'migration' => '2020_04_13_195424_insert_app_version_5_0_23',
                'batch' => 3,
            ),
            143 =>
            array (
                'id' => 55000144,
                'migration' => '2020_04_14_152705_insert_app_version_5_0_24',
                'batch' => 3,
            ),
            144 =>
            array (
                'id' => 55000145,
                'migration' => '2020_04_16_161656_create_importacoes_table',
                'batch' => 3,
            ),
            145 =>
            array (
                'id' => 55000146,
                'migration' => '2020_04_21_140539_insert_app_version_5_0_25',
                'batch' => 3,
            ),
            146 =>
            array (
                'id' => 55000147,
                'migration' => '2020_04_27_182334_insert_importacao_permissions_dados',
                'batch' => 3,
            ),
            147 =>
            array (
                'id' => 55000148,
                'migration' => '2020_04_27_205239_insert_importacao_codigoitens_dados',
                'batch' => 3,
            ),
            148 =>
            array (
                'id' => 55000149,
                'migration' => '2020_05_12_171935_insert_app_version_5_0_26',
                'batch' => 3,
            ),
            149 =>
            array (
                'id' => 55000150,
                'migration' => '2020_05_20_221358_alter_contrato_arquivos_varchar_text',
                'batch' => 3,
            ),
            150 =>
            array (
                'id' => 55000151,
                'migration' => '2020_05_25_131429_create_contratodespesaacessoria_table',
                'batch' => 3,
            ),
            151 =>
            array (
                'id' => 55000152,
                'migration' => '2020_05_25_132237_insert_tipodespesaacessoria_codigoitens_dados',
                'batch' => 3,
            ),
            152 =>
            array (
                'id' => 55000153,
                'migration' => '2020_05_25_140206_insert_recorrenciadespesaacessoria_codigoitens_dados',
                'batch' => 3,
            ),
            153 =>
            array (
                'id' => 55000154,
                'migration' => '2020_05_25_140450_insert_contratodespesaacessoria_permissions_dados',
                'batch' => 3,
            ),
            154 =>
            array (
                'id' => 55000155,
                'migration' => '2020_05_25_191102_alter_contratos_total_despesas_acumuladas_collumn',
                'batch' => 3,
            ),
            155 =>
            array (
                'id' => 55000156,
                'migration' => '2020_05_28_113608_insert_app_version_5_0_27',
                'batch' => 3,
            ),
            156 =>
            array (
                'id' => 55000157,
                'migration' => '2020_06_17_222839_add_situacao_to_users_table',
                'batch' => 3,
            ),
            157 =>
            array (
                'id' => 55000158,
                'migration' => '2020_06_18_130901_insert_app_version_5_0_28',
                'batch' => 3,
            ),
            158 =>
            array (
                'id' => 55000159,
                'migration' => '2020_06_22_171317_insert_padraosiafi_permissions_dados',
                'batch' => 3,
            ),
            159 =>
            array (
                'id' => 55000160,
                'migration' => '2020_06_29_160247_create_activity_log_expurgo_table',
                'batch' => 3,
            ),
            160 =>
            array (
                'id' => 55000161,
                'migration' => '2020_07_07_142453_insert_app_version_5_0_29',
                'batch' => 3,
            ),
        ));


    }
}
