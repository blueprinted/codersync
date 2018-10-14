#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/tw.sogou.com/$1 odin@10.135.30.141::odin/search/nginx/html/app/$1
rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/tw.sogou.com/$1 odin@10.135.30.199::odin/search/nginx/html/app/$1
exit;
