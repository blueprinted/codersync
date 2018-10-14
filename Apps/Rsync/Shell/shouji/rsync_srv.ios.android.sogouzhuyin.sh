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
	'13.113.194.19'
	'54.250.240.127'
)

for server in ${servers[@]}
do
	rsync -avzL --exclude=.svn -e "ssh -i /search/mycron/rsyncweb/pems/zhuyin_aws.pem" ${rsyncweb_dir}/shouji/srv-ios-android.sogouzhuyun.com/${file} ec2-user@${server}:/home/ec2-user/sogou/zhuyinshouji/${file}
done

exit;
