<?php

namespace App\Jobs;

use App\Exceptions\RequestFailException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class ProcessRequest
 * @package App\Jobs
 */
class ProcessRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var
     */
    protected $url;
    /**
     * @var
     */
    protected $requestId;

    /**
     * @var int
     */
    public $tries = 3;
    /**
     * @var int
     */
    public $retryAfter = 5;

    /**
     * Create a new job instance.
     *
     * @param $url
     */
    public function __construct($url, $requestId)
    {
        $this->url = $url;
        $this->requestId = $requestId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug('Sending Request');
        try {
            $request = Http::post($this->url, []);
            if ( !$request->failed() ) {
                Log::debug('Request sent correctly');
            } else {
                $request->throw();
            }
        } catch (\Exception $exception) {
            throw new \Exception("Request failed ".$exception->getMessage());
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        Log::info('Send notification of failure');
    }
}
