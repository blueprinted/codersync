#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/interface.shouji.test/$1 /search/nginx/html/dt_pinyin/interface.shouji.proxy/$1
exit;
