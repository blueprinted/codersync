#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

SOURCE_PATH='QQinput/config.qqpy.sogou.com/'
TARGET_PATH='/search/nginx/html/'
EXCLUDE_PATH=${shell_dir}'/qq/config_exc'
if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit 1
fi

if [ $# -gt 1 ] ; then
	if [[ !($1 =~ ^[0-9,]+$) ]] ; then
		echo "roc_sid error (must is number or ,)"
		exit 2
	fi

	URL="http://api.cms.shouji.sogou/api?sid=$1&type=data"
	source ${shell_dir}/get_servers_ip.sh
	for server in ${servers[@]}
	do
        rsync --exclude=.svn -zvual --exclude-from=${EXCLUDE_PATH} ${rsyncweb_dir}/${SOURCE_PATH}$2 odin@${server}::odin${TARGET_PATH}$2
	done
	exit;
fi

exit;
