#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 2 ] ; then
	echo "argu less than 2"
	exit 1
fi

if [[ !($1 =~ ^[0-9,]+$) ]] ; then
	echo "roc_sid error (must is number or ,)"
	exit 2
fi

if [[ !($2 =~ ^(api|skindl)\/\S*) ]] ; then
	echo " dir error (must begin with api/ or skindl/)"
	exit 3
fi

URL="http://api.cms.shouji.sogou/api?sid=$1&type=data"
source ${shell_dir}/get_servers_ip.sh

#servers=(
#	'10.142.44.183'
#	'10.142.71.128'
#	'10.143.57.232'
#	'10.143.58.224'
#)

for server in ${servers[@]}
do
	rsync -avzL --exclude=.svn --exclude-from=/search/mycron/rsyncweb/exclude/api/exclude.list ${rsyncweb_dir}/shouji/shoujiapi/$2 dt_pinyin@${server}::search/nginx/html/$2
done

exit;
