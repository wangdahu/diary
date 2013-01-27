#!/usr/bin/env bash

# get optional optimization instruction

opt=''
if [ "${1:0:2}" = "--" ]; then
    opt=${1:2}
    shift
else
    opt=ADVANCED_OPTIMIZATIONS
fi

out=$1
shift

echo "making $out"
cat $@ > "$out"

if [ "$opt" = NONE ]; then
    echo "No min"
else
    curl --data-urlencode "text@$out" uedsky.com/tool/min  -o "$out"
fi

echo "created $out"
