#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit 1
fi

if [[ !($1 =~ ^dist\/\S*) ]] ; then
	echo " dir error (must begin with dist/)"
	exit 2
fi

servers=(
	'10.138.8.193'
)

for server in ${servers[@]}
do
	rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/groupAdmin/$1 odin@${server}::odin/search/odin/webroot/groupAdmin/
done

exit;
