#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

#SOURCE_PATH='QQinput/config.qqpy.sogou.com/'
SOURCE_PATH='QQinput/test.config.qqpy.sogou.com/'
TARGET_PATH='/search/nginx/html/'
EXCLUDE_PATH=${shell_dir}'/qq/config_exc'
if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit 1
fi

source ${shell_dir}/qq/serverIP/test.config.qqpy.sogou.com.conf
for server in ${servers[@]}
do
	rsync --exclude=.svn -zvual --exclude-from=${EXCLUDE_PATH} ${rsyncweb_dir}/${SOURCE_PATH}$1 odin@${server}::odin${TARGET_PATH}$1
done

exit;
