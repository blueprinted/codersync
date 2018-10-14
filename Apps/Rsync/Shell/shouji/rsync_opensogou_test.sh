#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/open.shouji.sogou.com.test/$1 odin@10.142.118.222::search/odin/nginx/html/opensogou/$1
exit;
