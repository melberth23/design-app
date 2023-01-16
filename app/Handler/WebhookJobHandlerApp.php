<?php
namespace App\Handler;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class WebhookJobHandlerApp extends ProcessWebhookJob
{
	/**
	* The number of seconds the job can run before timing out.
	*
	* @var int
	*/
	public $timeout = 120;

	public function handle()
	{
		logger($this->webhookCall);

		sleep(10);
		logger("I am done");
	}
}