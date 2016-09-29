<?php

	namespace app\components\extensions\aws\sqs;

	interface SqsWorkerInterface
	{
		public function run();
	}