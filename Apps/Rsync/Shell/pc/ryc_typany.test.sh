#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit 1
fi

file=$1

if [ "$1" = "--force" ] ; then
	file="";
fi

servers=(
	'10.135.28.186'
)

for server in ${servers[@]}
do
	rsync -avzLu --exclude=.svn ${rsyncweb_dir}/sogoupc/typany.test/${file} odin@${server}::search/odin/rsyncweb/upload.typany.com.test_theme_studio/${file}
done

exit;
