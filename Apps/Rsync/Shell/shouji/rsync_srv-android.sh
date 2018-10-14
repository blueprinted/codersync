#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/srv-android.shouji/$1 /search/sac/srv-android.shouji/app/$1
exit;
