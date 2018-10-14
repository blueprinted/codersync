#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh
echo $shell_dir
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

for server in ${servers[@]}
do
	rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/wap_new/$2 odin@${server}::odin/search/nginx/html/wap/$2
done

exit;
