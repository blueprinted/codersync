#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

file=$1

if [ "$1" = "--force" ] ; then
	file="";
fi

servers=(
	'10.143.40.150'
)

for server in ${servers[@]}
do
	rsync -avzL --exclude=.svn ${rsyncweb_dir}/rebuilt/skins.pinyin.sogou.com.test/${file} odin@${server}::odin/search/odin/skins.pinyin.sogou/${file}
done

exit;
