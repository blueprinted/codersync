#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
    echo "argu less than 1"
    exit
fi

if [ -d "/search/web/wapdl/$1" ] ; then
    dir="/search/web/wapdl/$1"
elif [ -e "/search/web/wapdl/$1" ] ; then
    dir=$(dirname "/search/web/wapdl/$1")
else
    echo "/search/web/wapdl/$1 not exists !"
    exit;
fi

sshpass -p dt_pinyin ssh dt_pinyin@10.134.78.226 "sh /search/web/mkdir.sh $dir;exit"

##同步至网宿的增量比较目录
rsync -avzLu -rn --exclude=.svn /search/web/wapdl/$1 dt_pinyin@10.134.78.226::search/web/wapdl/$1
exit;
