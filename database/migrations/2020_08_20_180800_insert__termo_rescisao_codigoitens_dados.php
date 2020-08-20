<?php
use App\Models\Codigoitem;
use Illuminate\Database\Migrations\Migration;

class InsertTermoRescisaoCodigoitensDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigoitem = Codigoitem::create([
            'codigo_id' => 12,
            'descres' => '20',
            'descricao' => 'Termo de Rescisão'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Codigoitem::where([
            'codigo_id' => 12,
            'descres' => '20',
            'descricao' => 'Termo de Rescisão'
        ])->forceDelete();
    }
}
