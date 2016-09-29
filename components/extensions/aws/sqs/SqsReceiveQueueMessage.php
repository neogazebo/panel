<?php
	
	namespace app\components\extensions\aws\sqs;

	class SqsReceiveQueueMessage extends BaseSqsClient implements SqsWorkerInterface
	{
		private $number_of_messages;

		public function __construct($queue_url, $number_of_messages)
		{
			parent::__construct();
			$this->sqs_url = $queue_url;
			$this->number_of_messages = $number_of_messages;
		}

		public function run()
		{
			try
			{
				$result = $this->sqs_client->receiveMessage([
					'MaxNumberOfMessages' => $this->number_of_messages,
				    'QueueUrl' => $this->sqs_url,
				]);

				return $result;
			}
			catch(\Aws\Sqs\Exception\SqsException $e)
			{
				return $e->getMessage();
			}
		}
	}