<?php
	
	namespace app\components\extensions\aws\sqs;

	class SqsDeleteQueue extends BaseSqsClient implements SqsWorkerInterface
	{	
		public function __construct($url)
		{
			parent::__construct();
			$this->sqs_url = $url;
		}

		public function run()
		{
			try 
			{
				$result = $this->sqs_client->deleteQueue([
					'QueueUrl' => $this->sqs_url
				]);

				return $this->getStatus($result);
			} 
			catch (\Exception $e) 
			{
				echo $e->getMessage();
			}
		}
	}