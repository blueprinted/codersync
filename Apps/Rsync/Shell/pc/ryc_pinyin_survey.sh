#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
        echo "argu less than 1"
        exit
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
		rsync -av -u --exclude=.svn --progress ${rsyncweb_dir}/sogoupc/pinyin_survey/$2 ${server}::odin/search/nginx/html/dt_pinyin/q/$2
	done
	exit;
fi

cat ${shell_dir}/pc/server_ip/pc/pinyin_survey|xargs -t -n1 -i rsync -av -u --exclude=.svn --progress ${rsyncweb_dir}/sogoupc/pinyin_survey/$1 \{\}::odin/search/nginx/html/dt_pinyin/q/$1
