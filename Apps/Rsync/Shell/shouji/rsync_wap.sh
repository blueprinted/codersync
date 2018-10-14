#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn --exclude=log/* --exclude=sogou_temporary/* --exclude=tmp/* ${rsyncweb_dir}/shouji/shoujiwap/$1 odin@10.136.26.228::odin/search/nginx/html/dt_pinyin/wap/$1
rsync -avzL --exclude=.svn --exclude=log/* --exclude=sogou_temporary/* --exclude=tmp/* ${rsyncweb_dir}/shouji/shoujiwap/$1 odin@10.136.130.128::odin/search/nginx/html/dt_pinyin/wap/$1
rsync -avzL --exclude=.svn --exclude=log/* --exclude=sogou_temporary/* --exclude=tmp/* ${rsyncweb_dir}/shouji/shoujiwap/$1 odin@10.136.114.223::odin/search/nginx/html/dt_pinyin/wap/$1
rsync -avzL --exclude=.svn --exclude=log/* --exclude=sogou_temporary/* --exclude=tmp/* ${rsyncweb_dir}/shouji/shoujiwap/$1 odin@10.142.108.206::odin/search/nginx/html/dt_pinyin/wap/$1
exit;
