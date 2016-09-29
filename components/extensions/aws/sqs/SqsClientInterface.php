<?php

	namespace app\components\extensions\aws\sqs;

	interface SqsClientInterface
	{
		public function createQueue($attributes, $queue_name);
		public function getQueueUrl();
		public function getQueueMessage($queue_url, $message_limit);
		public function sendQueueMessage($queue_url, $message);
		public function deleteQueueMessage($queue_url, $receipt_handler);
	}