#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

servers=(
	'10.142.119.225'
)
sh ${shell_dir}/shouji/goAppRestart/srv-ios-go_test_Restart.sh "${servers[@]}"

exit;
