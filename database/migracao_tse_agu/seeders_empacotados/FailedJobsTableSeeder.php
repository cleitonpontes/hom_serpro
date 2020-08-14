<?php

use Illuminate\Database\Seeder;

class FailedJobsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {



        \DB::table('failed_jobs')->insert(array (
            0 =>
            array (
                'id' => 55000001,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\UserMailPasswordJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":null,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\UserMailPasswordJob","command":"O:28:\\"App\\\\Jobs\\\\UserMailPasswordJob\\":9:{s:7:\\"\\u0000*\\u0000user\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":4:{s:5:\\"class\\";s:23:\\"App\\\\Models\\\\BackpackUser\\";s:2:\\"id\\";i:2;s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"pgsql\\";}s:8:\\"\\u0000*\\u0000dados\\";a:4:{s:5:\\"email\\";s:28:\\"Henrique.Teixeira@tse.jus.br\\";s:3:\\"cpf\\";s:14:\\"783.403.571-15\\";s:4:\\"nome\\";s:26:\\"HENRIQUE DA SILVA TEIXEIRA\\";s:5:\\"senha\\";s:8:\\"NOVA8898\\";}s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
                'exception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\BackpackUser]. in /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:452
Stack trace:
#0 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/SerializesAndRestoresModelIdentifiers.php(85): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()
#1 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/SerializesAndRestoresModelIdentifiers.php(55): App\\Jobs\\UserMailPasswordJob->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/SerializesModels.php(45): App\\Jobs\\UserMailPasswordJob->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))
#3 [internal function]: App\\Jobs\\UserMailPasswordJob->__wakeup()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(42): unserialize(\'O:28:"App\\\\Jobs\\\\...\')
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#11 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#17 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#19 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#20 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#21 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#23 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#24 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#25 {main}',
                'failed_at' => '2020-04-27 23:09:39',
            ),
            1 =>
            array (
                'id' => 55000002,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-04-28 08:40:05',
            ),
            2 =>
            array (
                'id' => 55000003,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-04-29 08:40:04',
            ),
            3 =>
            array (
                'id' => 55000004,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-04-30 08:40:03',
            ),
            4 =>
            array (
                'id' => 55000005,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-01 08:40:03',
            ),
            5 =>
            array (
                'id' => 55000006,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-02 08:40:03',
            ),
            6 =>
            array (
                'id' => 55000007,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-03 08:40:03',
            ),
            7 =>
            array (
                'id' => 55000008,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-04 08:40:03',
            ),
            8 =>
            array (
                'id' => 55000009,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-05 08:40:03',
            ),
            9 =>
            array (
                'id' => 55000010,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-06 08:40:03',
            ),
            10 =>
            array (
                'id' => 55000011,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-07 08:40:04',
            ),
            11 =>
            array (
                'id' => 55000012,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-08 08:40:04',
            ),
            12 =>
            array (
                'id' => 55000013,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-09 08:40:03',
            ),
            13 =>
            array (
                'id' => 55000014,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-10 08:40:03',
            ),
            14 =>
            array (
                'id' => 55000015,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-11 08:40:04',
            ),
            15 =>
            array (
                'id' => 55000016,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-12 08:40:05',
            ),
            16 =>
            array (
                'id' => 55000017,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-13 08:40:05',
            ),
            17 =>
            array (
                'id' => 55000018,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-14 08:40:03',
            ),
            18 =>
            array (
                'id' => 55000019,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-15 08:40:04',
            ),
            19 =>
            array (
                'id' => 55000020,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-16 08:40:04',
            ),
            20 =>
            array (
                'id' => 55000021,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-17 08:40:04',
            ),
            21 =>
            array (
                'id' => 55000022,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-18 08:40:04',
            ),
            22 =>
            array (
                'id' => 55000023,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-19 08:40:03',
            ),
            23 =>
            array (
                'id' => 55000024,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-20 08:40:04',
            ),
            24 =>
            array (
                'id' => 55000025,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-21 08:40:04',
            ),
            25 =>
            array (
                'id' => 55000026,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-22 08:40:04',
            ),
            26 =>
            array (
                'id' => 55000027,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-23 08:40:04',
            ),
            27 =>
            array (
                'id' => 55000028,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-24 08:40:04',
            ),
            28 =>
            array (
                'id' => 55000029,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-25 08:40:06',
            ),
            29 =>
            array (
                'id' => 55000030,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-26 08:40:05',
            ),
            30 =>
            array (
                'id' => 55000031,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-27 08:40:03',
            ),
            31 =>
            array (
                'id' => 55000032,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-28 08:40:03',
            ),
            32 =>
            array (
                'id' => 55000033,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-29 08:40:03',
            ),
            33 =>
            array (
                'id' => 55000034,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-30 08:40:02',
            ),
            34 =>
            array (
                'id' => 55000035,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-05-31 08:40:06',
            ),
            35 =>
            array (
                'id' => 55000036,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-01 08:40:03',
            ),
            36 =>
            array (
                'id' => 55000037,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-02 08:40:03',
            ),
            37 =>
            array (
                'id' => 55000038,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-03 08:40:03',
            ),
            38 =>
            array (
                'id' => 55000039,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-04 08:40:04',
            ),
            39 =>
            array (
                'id' => 55000040,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-05 08:40:04',
            ),
            40 =>
            array (
                'id' => 55000041,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-06 08:40:06',
            ),
            41 =>
            array (
                'id' => 55000042,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-07 08:40:03',
            ),
            42 =>
            array (
                'id' => 55000043,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-08 08:40:04',
            ),
            43 =>
            array (
                'id' => 55000044,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-09 08:40:03',
            ),
            44 =>
            array (
                'id' => 55000045,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-10 08:40:05',
            ),
            45 =>
            array (
                'id' => 55000046,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-11 08:40:03',
            ),
            46 =>
            array (
                'id' => 55000047,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-12 08:40:05',
            ),
            47 =>
            array (
                'id' => 55000048,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-13 08:40:05',
            ),
            48 =>
            array (
                'id' => 55000049,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-14 08:40:03',
            ),
            49 =>
            array (
                'id' => 55000050,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-15 08:40:04',
            ),
            50 =>
            array (
                'id' => 55000051,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-16 08:40:03',
            ),
            51 =>
            array (
                'id' => 55000052,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-17 08:40:03',
            ),
            52 =>
            array (
                'id' => 55000053,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-18 08:40:03',
            ),
            53 =>
            array (
                'id' => 55000054,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-19 08:40:03',
            ),
            54 =>
            array (
                'id' => 55000055,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-20 08:40:04',
            ),
            55 =>
            array (
                'id' => 55000056,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-21 08:40:02',
            ),
            56 =>
            array (
                'id' => 55000057,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-22 08:40:03',
            ),
            57 =>
            array (
                'id' => 55000058,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-23 08:40:04',
            ),
            58 =>
            array (
                'id' => 55000059,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-24 08:40:04',
            ),
            59 =>
            array (
                'id' => 55000060,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-25 08:40:04',
            ),
            60 =>
            array (
                'id' => 55000061,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-26 08:40:03',
            ),
            61 =>
            array (
                'id' => 55000062,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-27 08:40:04',
            ),
            62 =>
            array (
                'id' => 55000063,
                'connection' => 'database',
                'queue' => 'default',
                'payload' => '{"displayName":"App\\\\Jobs\\\\MigracaoempenhoJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"timeout":7200,"timeoutAt":null,"data":{"commandName":"App\\\\Jobs\\\\MigracaoempenhoJob","command":"O:27:\\"App\\\\Jobs\\\\MigracaoempenhoJob\\":9:{s:7:\\"timeout\\";i:7200;s:8:\\"\\u0000*\\u0000ug_id\\";s:1:\\"1\\";s:6:\\"\\u0000*\\u0000job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:5:\\"delay\\";N;s:7:\\"chained\\";a:0:{}}"}}',
            'exception' => 'ErrorException: Invalid argument supplied for foreach() in /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php:54
Stack trace:
#0 /var/www/html/sc/app/Jobs/MigracaoempenhoJob.php(54): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'Invalid argumen...\', \'/var/www/html/s...\', 54, Array)
#1 [internal function]: App\\Jobs\\MigracaoempenhoJob->handle()
#2 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#3 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#4 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#5 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#6 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)
#7 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#8 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\MigracaoempenhoJob))
#9 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#10 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\MigracaoempenhoJob), false)
#11 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)
#12 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\\Queue\\Jobs\\Job->fire()
#13 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))
#14 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))
#15 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))
#16 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')
#17 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->handle()
#18 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#20 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))
#21 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)
#22 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\\Container\\Container->call(Array)
#23 /var/www/html/sc/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#24 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))
#25 /var/www/html/sc/vendor/symfony/console/Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#26 /var/www/html/sc/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#27 /var/www/html/sc/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#28 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#29 /var/www/html/sc/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#30 /var/www/html/sc/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))
#31 {main}',
                'failed_at' => '2020-06-28 08:40:05',
            ),
        ));


    }
}
