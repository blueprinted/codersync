#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

SOURCE_PATH='QQinput/config.android.qqpy.sogou.com/QQinput/'
TARGET_PATH='/nginx/html/dt_pinyin/config.android.qqpy.sogou.com/QQinput/'
EXCLUDE_PATH=${shell_dir}'/qq/config_android_exc'
if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit 1
fi

source ${shell_dir}/qq/config.android.qqpy.sogou.com.conf
for server in ${servers[@]}
do
	rsync --exclude=.svn -zvual --exclude-from=${EXCLUDE_PATH} ${rsyncweb_dir}/${SOURCE_PATH}$1 odin@${server}::dt_pinyin${TARGET_PATH}$1
done

exit;
