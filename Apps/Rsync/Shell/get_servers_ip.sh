#/bin/bash

#URL='http://api.cms.shouji.sogou/api?sid=66272&type=data'

RESP=$(curl -m 5 --connect-timeout 5 --write-out http_code=%{http_code} -s ${URL})

HTTP_CODE=$(echo $RESP | sed -e 's/.*http_code=//g')

if [ "$HTTP_CODE" != 200 ];then
	echo "request ${URL} http_code error (${HTTP_CODE})"
	exit 1
fi

##取data内容
RESP=$(echo $RESP | sed -r 's/^.+"data":\[(.+)\].+/\1/g')

if [ ${#RESP} -lt 1 ];then
	echo "have no servers ip"
	exit 2
fi

##将ip替换出来分行显示并从这些行中将ip抓取出来
servers=$(echo ${RESP} | sed -r 's/"ip":"([0-9\.]+)"/\n\1\n/g' | grep -P "([0-9]{1,3}[\.]){3}[0-9]{1,3}" | grep -v '^$')

#for server in ${servers[@]}
#do
#	echo ${server}
#done
