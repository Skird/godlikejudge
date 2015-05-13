#!/bin/bash

for f in `ls -l | grep ^- | awk '{ print $9 }'`
do
	echo $f
	sed s/\\r// $f > $f.temporary
	mv $f.temporary $f
done
