<?php
	
	namespace app\components\extensions\aws\sqs;

	class SqsDeleteQueueMessage extends BaseSqsClient implements SqsWorkerInterface
	{
		private $receipt_handle;

		public function __construct($queue_url, $receipt_handle)
		{
			parent::__construct();
			$this->sqs_url = $queue_url;
			$this->receipt_handle = $receipt_handle;
		}

		public function run()
		{
			try
			{
				$result = $this->sqs_client->deleteMessage([
				    // QueueUrl is required
				    'QueueUrl' => $this->sqs_url,
				    // ReceiptHandle is required
				    'ReceiptHandle' => $this->receipt_handle,
				]);

				return $this->getStatus($result);
			}
			catch(\Exception $e)
			{
				return $e->getMessage();
			}
		}
	}