<?php
	
	namespace app\components\extensions\aws\sqs;

	class SqsGetQueueUrl extends BaseSqsClient implements SqsWorkerInterface
	{
		public function __construct()
		{
			parent::__construct();
			$this->sqs_queue_name = env('SQS_QUEUE_NAME', 'test');
			$this->sqs_owner_id = env('SQS_OWNER_ID', 'test');
		}

		public function run()
		{
			try
			{
				/*
				$result = $this->sqs_client->getQueueUrl([
				    // QueueName is required
				    'QueueName' => $this->$sqs_queue_name,
				    'QueueOwnerAWSAccountId' => $this->$sqs_owner_id,
				]);
				*/

				$result = 'http://url_test';

				return $result;
			}
			catch(\Exception $e)
			{
				return $e->getMessage();
			}
		}
	}