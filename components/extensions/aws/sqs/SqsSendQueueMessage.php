<?php
	
	namespace app\components\extensions\aws\sqs;

	class SqsSendQueueMessage extends BaseSqsClient implements SqsWorkerInterface
	{
		private $sqs_message;

		public function __construct($queue_url, $message)
		{
			parent::__construct();
			$this->sqs_url = $queue_url;
			$this->sqs_message = $message;
		}

		public function run()
		{
			try
			{
				$result = $this->sqs_client->sendMessage([
				    'QueueUrl'    => $this->sqs_url,
				    'MessageBody' => $this->sqs_message,
				]);

				return $this->getStatus($result);
			}
			catch(\Exception $e)
			{
				return $e->getMessage();
			}
		}
	}