#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/srv-android.shouji.test/$1 dt_pintin@10.142.90.169::search/odin/html/dt_pinyin/srvandroid/$1
exit;
