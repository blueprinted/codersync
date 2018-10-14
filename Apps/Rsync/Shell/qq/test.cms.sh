#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

SOURCE_PATH='QQinput/test.cms/'
TARGET_PATH='/nginx/html/cms_test/'
EXCLUDE_PATH=${shell_dir}'/qq/config_cms_exc'
if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit 1
fi

source ${shell_dir}/qq/serverIP/test.config.cms.conf
for server in ${servers[@]}
do
	rsync --exclude=.svn -zvual --exclude-from=${EXCLUDE_PATH} ${rsyncweb_dir}/${SOURCE_PATH}$1 odin@${server}::search2${TARGET_PATH}$1
done

exit;
