#!/bin/sh

if [ $# -lt 1 ] ; then
    echo "argu less than 1"
    exit
fi

if [ ! -d $1 ] ; then
    mkdir -m 0755 -p $1
    echo "mkdir $1 done"
fi