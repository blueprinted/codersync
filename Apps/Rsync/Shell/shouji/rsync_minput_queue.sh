#!/bin/bash

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
    echo "argu less than 1"
    exit
fi

rsync -avzL --exclude=.svn  ${rsyncweb_dir}/shouji/minput_queue/$1 odin@10.142.109.210::search/odin/queue/$1
rsync -avzL --exclude=.svn  ${rsyncweb_dir}/shouji/minput_queue/$1 odin@10.142.117.142::search/odin/queue/$1
