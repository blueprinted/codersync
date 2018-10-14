#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync --exclude=.svn -avzLu --progress /search/web/wapdl/$1 repos01.cdn.sjs.ted::dt_pinyin/wapdl/$1 && ssh -l dt_pinyin repos01.cdn.sjs.ted "cd /search/deploy/push/dt_pinyin; sogou-cdn-deploy push wapdl/$1;exit"
exit;
