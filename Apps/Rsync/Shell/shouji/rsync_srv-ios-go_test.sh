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

rsync --exclude=.svn -zvual ${rsyncweb_dir}/shouji/srv-ios-go.test/$1 odin@10.138.10.190::odin/search/odin/daemon/srv-ios-go/$1

exit;
