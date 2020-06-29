<?php

use App\Jobs\LimpaActivityLogJob;
use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class ActivityLogExpurgoSeederTableSeeder extends Seeder
{
    public function run()
    {
        /*        
        DB::statement('INSERT INTO activity_log_expurgo(
            id, log_name, description, subject_id, subject_type, ip, causer_id, causer_type, properties, created_at, updated_at) 
            SELECT id, log_name, description, subject_id, subject_type, ip, causer_id, causer_type, properties, created_at, updated_at 
            FROM activity_log WHERE created_at < (CURRENT_DATE - 30)');
        
        DB::statement('DELETE FROM activity_log WHERE created_at < (CURRENT_DATE - 30)');
        
*/
$importaAL = new LimpaActivityLogJob();
$importaAL->handle();
    }
}
