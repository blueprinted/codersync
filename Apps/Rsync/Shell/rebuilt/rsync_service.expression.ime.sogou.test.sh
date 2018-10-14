#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit 1
fi

rsync -avzL --exclude=.svn ${rsyncweb_dir}/rebuilt/service.expression.ime.sogou.test/$1 odin@10.138.8.166::odin/search/odin/nginx/html/expression/$1
exit;