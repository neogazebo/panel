<?php
	
	namespace app\components\extensions\aws\sqs;

	class SqsClient implements SqsClientInterface
	{
		public function createQueue($attributes, $name)
		{
			$client = new SqsCreateQueue($attributes, $name);
			return $client->run();
		}

		public function getQueueUrl()
		{
			$client = new SqsGetQueueUrl();
			$client->run();
		}

		public function getQueueMessage($queue_url, $message_limit)
		{
			$client = new SqsReceiveQueueMessage($queue_url, $message_limit);
			return $client->run();
		}

		public function sendQueueMessage($queue_url, $message)
		{
			$client = new SqsSendQueueMessage($queue_url, $message);
			return $client->run();
		}

		public function deleteQueueMessage($queue_url, $receipt_handler)
		{
			$client = new SqsDeleteQueueMessage($queue_url, $receipt_handler);
			return $client->run();
		}
	}