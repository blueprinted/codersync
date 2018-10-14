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
		rsync -avzL --exclude=.svn ${rsyncweb_dir}/rebuilt/skins.pinyin.sogou.com/$2 odin@${server}::odin/search/odin/nginx/html/code/$2
	done
	exit;
fi

file=$1

if [ "$1" = "--force" ] ; then
	file="";
fi

servers=(
	'10.143.41.213'
	'10.143.47.176'
)

for server in ${servers[@]}
do
	rsync -avzL --exclude=.svn ${rsyncweb_dir}/rebuilt/skins.pinyin.sogou.com/${file} odin@${server}::odin/search/odin/nginx/html/code/${file}
done

exit;
