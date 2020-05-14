<?php

namespace App\Console\Commands;

use App\Jobs\ProcessRequest;
use Illuminate\Console\Command;

/**
 * Class SendPostRequest
 * @package App\Console\Commands
 */
class SendPostRequest extends Command
{
    /**
     * Url Endpoint
     */
    const URL_ENDPOINT = 'https://atomic.incfile.com/fakepost';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'incfile:make-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command sends a simple POST request to IncFile atomic fake post';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $requests = $this->ask('How many request do you want?');
        $bar = $this->output->createProgressBar();
        $bar->start();
        for ($i = 1; $i <= $requests; $i++) {
            $job = (new ProcessRequest(self::URL_ENDPOINT, $i));
            dispatch($job)->onQueue('requests');
            $bar->advance();
        }
        $bar->finish();
        $this->info('');
        $this->info('Jobs dispatched');
    }
}
