#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit 1
fi

if [ $# -gt 1 ] ; then
	if [[ !($1 =~ ^[0-9,]+$) ]] ; then
		echo "roc_sid error (must is number or ,)"
		exit 2
	fi
	if [[ "$2" != "bin/"* && "$2" != "conf/"* ]] ;then
		echo "Error:only bin/ or conf/ "
		exit 3
	fi
	URL="http://api.cms.shouji.sogou/api?sid=$1&type=data"
	source ${shell_dir}/get_servers_ip.sh
	for server in ${servers[@]}
	do
		rsync --exclude=.svn -zvual ${rsyncweb_dir}/shouji/srv-ios-go/$2 odin@${server}::odin/search/odin/daemon/srv-ios-go/$2
	done
	exit;
fi

if [[ "$1" != "bin/"* && "$1" != "conf/"* ]] ;then
	echo "Error:only bin/ or conf/ "
	exit 3
fi

exit;
