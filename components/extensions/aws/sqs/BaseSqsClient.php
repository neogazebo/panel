<?php
	
	namespace app\components\extensions\aws\sqs;

	use Yii;

	use Aws\Sqs\SqsClient as AwsSqsClient;

	use Aws\Result;

	abstract class BaseSqsClient
	{
		protected $sqs_client;
		protected $sqs_url;
		protected $sqs_queue_name;
		protected $sqs_owner_id;
		protected $result;

		public function __construct()
		{
			$this->sqs_client = new AwsSqsClient([
			    'version' => 'latest',
			    'region'  => Yii::$app->params['AWS_REGION'],
			    'credentials' => [
			        'key'    => Yii::$app->params['AWS_ACCESS_KEY_ID'],
			        'secret' => Yii::$app->params['AWS_SECRET_ACCESS_KEY']
			    ]
			]);
		}

		public function getStatus($result)
		{
			$result_metadata = $result->get('@metadata');
			return $result_metadata['statusCode'];
		}
	}