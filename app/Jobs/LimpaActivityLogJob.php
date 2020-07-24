<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class LimpaActivityLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $dias;
    private $date;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(){   
        $this->dias = -30;
        $this->date = Carbon::parse(Carbon::now())->addDays($this->dias)->format('d-m-Y');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){

        DB::transaction(function () {
            $this->populaActivityLogExpurgo();
            $this->removeActivityLog();    
        });
        
    }

    private function populaActivityLogExpurgo(){
        DB::table('activity_log_expurgo')->insertUsing(
            ['id','log_name','description','subject_id','subject_type','ip',
            'causer_id','causer_type','properties','created_at', 'updated_at'],
            function (QueryBuilder $query) {
                $query->select(
                    ['id','log_name','description','subject_id','subject_type','ip',
                    'causer_id','causer_type','properties','created_at', 'updated_at'])
                    ->from('activity_log')
                    ->whereDate('created_at', '<=', $this->date);
            }
        );
    }

    private function removeActivityLog(){
        return DB::table('activity_log')->whereDate('created_at', '<=', $this->date)->delete();
    }
}
