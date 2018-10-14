#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn --exclude=sogou_temporary/* --exclude=tmp/* ${rsyncweb_dir}/shouji/pinyin.cn.test/$1 /search/nginx/html/dt_pinyin/pinyin.cn/$1
exit;
