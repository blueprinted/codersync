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

URL="http://api.cms.shouji.sogou/api?sid=$1&type=data"
source ${shell_dir}/get_servers_ip.sh

#servers=(
#	'10.142.89.162'
#	'10.142.104.215'
#	'10.142.109.182'
#	'10.142.91.237'
#)

for server in ${servers[@]}
do
	rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/shoujisapp/$2 odin@${server}::odin/search/nginx/html/sweb/sapp/$2
done

exit;
