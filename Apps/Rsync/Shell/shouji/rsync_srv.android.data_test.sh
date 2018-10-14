#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

file=$1

if [ "$1" = "--force" ] ; then
	file="";
fi

servers=(
	'10.138.34.201'
)

for server in ${servers[@]}
do
	rsync -avzL --exclude=.svn /search/nginx/html/dt_pinyin/yadie_proxy/data/webdata/srv.android/${file} odin@${server}::search/odin/webdata/${file}
done

exit;
