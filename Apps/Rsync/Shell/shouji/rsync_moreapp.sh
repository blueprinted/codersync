#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
    echo "argu less than 1"
    exit 1
fi

if [[ !($1 =~ ^(delcache|interface|jumoku|kube|mcqqueue|mincms|myeditor|soko|wapdl|webui_demo|tool|answer)\/\S*) ]] ; then
	echo "dir error (must begin with delcache/ or interface/ etc..)"
	exit 3
fi

rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/moreapp/$1 /search/nginx/html/dt_pinyin/$1
