#!/bin/sh

pwd=$(cd $(dirname $0); pwd)
source ${pwd}/../common.sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/mycron/$1 /search/mycron/$1

if [[ $1 =~ ^srv.ios.mk\/\S* ]] ; then
	target=$1;
	target=${target/srv.ios.mk\//srv.ios.mk.proxy\/}
	rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/mycron/$1 /search/mycron/${target}
fi

#servers=(
#	'10.143.63.68'
#)

URL="http://api.cms.shouji.sogou/api?sid=79447&type=data"
source ${shell_dir}/get_servers_ip.sh

for server in ${servers[@]}
do
	rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/mycron/$1 odin@${server}::odin/search/odin/cron/shoujicron/$1
	if [[ $1 =~ ^srv.ios.mk\/\S* ]] ; then
		target=$1;
		target=${target/srv.ios.mk\//srv.ios.mk.proxy\/}
		rsync -avzL --exclude=.svn ${rsyncweb_dir}/shouji/mycron/$1 odin@${server}::odin/search/odin/cron/shoujicron/${target}
	fi
done

exit;
