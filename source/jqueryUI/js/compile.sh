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
    curl \
        -o "$out" \
        --data-urlencode "text@$out" \
        http://uedsky.com/tool/min
    # curl \
    #     -o "$out" \
    #     --data-urlencode "js_code@$out" \
    #     -d compilation_level=$opt \
    #     -d output_info=compiled_code \
    #     -d output_format=text \
    #     -d 'output_wrapper=(function(){%25output%25}).call(this);' \
    #     http://closure-compiler.uedsky.com/compile
fi

echo "created $out"
