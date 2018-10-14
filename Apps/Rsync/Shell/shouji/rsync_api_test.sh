#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
    echo "argu less than 1"
    exit
fi

if [[ !($1 =~ ^(api|skindl)\/\S*) ]] ; then
	echo " dir error (must begin with api/ or skindl/)"
	exit
fi

target=$1;
target=${target/api\//api_test\/}

rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/shoujiapi_test/$1 /search/nginx/html/dt_pinyin/${target}
