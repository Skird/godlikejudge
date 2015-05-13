#!/bin/bash

cnt=1
for f in *.in
do
	to=$cnt
	if [ $cnt -lt 10 ]
	then
		to=00$to
	else
		if [ $cnt -lt 100 ]
		then 
			to=0$to
		fi
	fi
	let "cnt+=1"
	
	ans=${f%.in}.out
	
	mv $f $to
	mv $ans $to.ans
done
