#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -gt 0 ] ; then
	if [[ !($1 =~ ^[0-9,]+$) ]] ; then
		echo "roc_sid error (must is number or ,)"
		exit 1
	fi
	URL="http://api.cms.shouji.sogou/api?sid=$1&type=data"
	source ${shell_dir}/get_servers_ip.sh
	sh ${shell_dir}/goAppRestart.sh "${servers[@]}"
	exit;
fi

source ${shell_dir}/rebuilt/servers_download.pinyin.sogou.com.conf
sh ${shell_dir}/goAppRestart.sh "${servers[@]}"

exit;