# Pull repo's from BitBucket
#!/usr/bin/env bash=
#curl -u ${USER}:${PASS} ${SOURCE} --compressed --output ./logs/repos.json  -vvv >> ./logs/curl.log

function jsonval {
    temp=`echo $json | sed 's/\\\\\//\//g' | sed 's/[{}]//g' | awk -v k="text" '{n=split($0,a,","); for (i=1; i<=n; i++) print a[i]}' | sed 's/\"\:\"/\|/g' | sed 's/[\,]/ /g' | sed 's/\"//g' | grep -w $prop`
    echo ${temp##*|}
}

json=`curl -s  -u ${USER}:${PASS} ${SOURCE}`
prop='name'
picurl=`jsonval`

echo ${picurl}

#`curl -s -X GET $picurl -o $1.png`