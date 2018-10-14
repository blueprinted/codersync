#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -ge 0 ] ; then
	servers=(
	'10.138.34.201'
	)
	sh ${shell_dir}/rebuilt/goAppRestart/srv-android-Restart.test.sh "${servers[@]}"
	exit;
fi

exit;
