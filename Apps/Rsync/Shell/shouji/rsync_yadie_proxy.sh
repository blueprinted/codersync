#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/yadie_proxy/$1 /search/nginx/html/dt_pinyin/yadie_proxy/$1
exit;