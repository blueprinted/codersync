#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

SOURCE_PATH='QQinput/config.android.qqpy.sogou.com/'
TARGET_PATH='/nginx/html/dt_pinyin/config.android.qqpy.sogou.com/'
EXCLUDE_PATH=${shell_dir}'/qq/config_android_exc'
if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit 1
fi

if [ $# -gt 1 ] ; then
	if [[ !($1 =~ ^[0-9,]+$) ]] ; then
		echo "roc_sid error (must is number)"
		exit 2
	fi

	URL="http://api.cms.shouji.sogou/api?sid=$1&type=data"
	source ${shell_dir}/get_servers_ip.sh
	for server in ${servers[@]}
	do
		rsync --exclude=.svn -zvual --exclude-from=${EXCLUDE_PATH} ${rsyncweb_dir}/${SOURCE_PATH}$2 odin@${server}::dt_pinyin${TARGET_PATH}$2
	done
	exit;
fi

file=$1

source ${shell_dir}/qq/config.android.qqpy.sogou.com.conf
for server in ${servers[@]}
do
	rsync --exclude=.svn -zvual --exclude-from=${EXCLUDE_PATH} ${rsyncweb_dir}/${SOURCE_PATH}${file} odin@${server}::dt_pinyin${TARGET_PATH}${file}
done

exit;
