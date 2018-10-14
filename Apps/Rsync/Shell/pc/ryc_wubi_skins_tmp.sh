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
		rsync -n -av -u --exclude=.svn --progress ${rsyncweb_dir}/sogoupc/wubi_skins/$2 ${server}::odin/search/code/wubi/$2
	done
	exit;
fi

cat ${shell_dir}/pc/server_ip/pc/skins|xargs -t -n1 -i rsync -n -av -u --exclude=.svn --progress ${rsyncweb_dir}/sogoupc/wubi_skins/$1 \{\}::odin/search/code/wubi/$1
