#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn --exclude=sogou_temporary/* --exclude=tmp/* --exclude=opensogou/* --exclude=opensogou_v2.0/* --exclude=opensogou_v2.1/* --exclude=opensogou_v3.0/* --exclude=opensogou_v4.0/* ${rsyncweb_dir}/shouji/shouji.sogou.com.test/$1 /search/nginx/html/dt_pinyin/sweb_new/$1
exit;
