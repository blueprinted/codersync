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
	URL="http://api.cms.shouji.sogou/api?sid=$1&type=data"
	source ${shell_dir}/get_servers_ip.sh
	for server in ${servers[@]}
	do
		rsync -avzL --exclude=.svn ${rsyncweb_dir}/rebuilt/service.ywz.ime.sogou/$2 odin@${server}::odin/search/odin/nginx/html/ywz/$2
	done
	exit;
fi
echo "argu less than 2 )"
exit;
