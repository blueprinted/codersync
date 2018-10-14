#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
    echo "argu less than 1"
    exit
fi

rsync -avzL --exclude=.svn /search/rsyncweb/SERVER/$1 odin@10.143.40.150::odin/search/odin/code/$1

exit;
