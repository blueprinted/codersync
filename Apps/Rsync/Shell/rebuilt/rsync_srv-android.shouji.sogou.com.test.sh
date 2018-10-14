#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit 1
fi

if [[ "$1" != "bin/"* && "$1" != "conf/"* ]] ;then
	echo "Error:only bin/ or conf/ "
	exit 3
fi

servers=(
'10.138.34.201'
)

for server in ${servers[@]}
do
	rsync --exclude=.svn -zvual ${rsyncweb_dir}/rebuilt/srv-android.shouji.sogou.com.test/$1 odin@${server}::search/odin/daemon/srv-android/$1
done

exit;
