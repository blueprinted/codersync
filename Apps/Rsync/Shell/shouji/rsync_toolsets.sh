#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/toolsets/$1 odin@10.143.56.211::search/odin/nginx/html/$1

#if [[ ($1 =~ ^Public\/\S*) ]] ; then
#	target=$1
#	target=${target/Public\//}
#	rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/toolsets/$1 /search/web/wapdl/static/toolsets/${target}
#fi

exit;
