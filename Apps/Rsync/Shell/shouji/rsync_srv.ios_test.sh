#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/srv.ios.shouji.test/$1 dt_pintin@10.142.114.207::search/nginx/html/srv.ios/$1
exit;
