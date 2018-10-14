#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/tw.sogou.com.test/$1 sunzhenyu@10.142.111.238::search/odin/www/sogou/srv.tw/$1
exit;
