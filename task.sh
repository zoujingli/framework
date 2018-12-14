#!/usr/bin/env bash
command="php $(cd `dirname $0`;pwd)/think queue:listen"
ps aux|grep -v grep|grep "$command" > /dev/null
if [ $? != 0 ]; then
echo "没有发现进程，正在启动进程..."
$command > /dev/null 2>&1 &
ps aux|grep -v grep|grep "$command" > /dev/null
if [ $? = 0 ]; then echo "进程启动成功！";else echo "进程启动失败！";fi;
else
echo "进程已经存在，不需要重新启动进程！"
fi