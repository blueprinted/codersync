#!/bin/sh

if [ $# -lt 1 ] ; then
	echo "argu less than 1"
	exit
fi

rsync -avzL --exclude=.svn /search/odin/rsyncweb/pctools/$1 /search/odin/html/$1

exit;
