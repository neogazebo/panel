<?php
	
	namespace app\components\extensions\aws\sqs;

	class SqsCreateQueue extends BaseSqsClient implements SqsWorkerInterface
	{
		private $new_queue_name;
		private $new_queue_attr;
		
		public function __construct($attributes, $name)
		{
			parent::__construct();
			$this->new_queue_attr = $attributes;
			$this->new_queue_name = $name;
		}

		public function run()
		{
			try
			{
				$result = $this->sqs_client->createQueue([
					'Attributes' => $this->new_queue_attr,
					'QueueName' => $this->new_queue_name
				]);

				return $this->getStatus($result);
			}
			catch(\Exception $e)
			{
				echo $e->getMessage();
			}
		}
	}