
#!/usr/bin/env bash

INIT_EXECUTED="success"

if [ "$YII_ENV" != "" ]; then
  php init --env=$YII_ENV --overwrite=All
  echo "Init script for $YII_ENV environment is executed successfully."
elif [ $1 != "" ]; then
  php init --env=$1 --overwrite=All
  echo "Init script for $YII_ENV environment is executed successfully."
else
  INIT_EXECUTED="failed"
  echo 'Init script is failed to execute.'
fi
