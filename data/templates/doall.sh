#!/bin/bash

function compile()
{
	g++ $1.cpp -o $1 -O2 -static -fno-optimize-sibling-calls -fno-strict-aliasing -DONLINE_JUDGE -lm -s -x c++
}

function fail()
{
	echo $1
	exit 1
}

compile checker || fail "Cannot compile checker"
compile grade || fail "Cannot compile grader"
echo "Done"
