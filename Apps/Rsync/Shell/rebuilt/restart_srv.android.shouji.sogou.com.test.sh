#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

servers=(
	'10.148.10.190'
)
sh ${shell_dir}/rebuilt/goAppRestart/srv.android-Restart.test.sh "${servers[@]}"

exit;
