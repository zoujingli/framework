#!/usr/bin/env bash
command="php $(cd `dirname $0`;pwd)/think queue:listen"
ps aux|grep -v grep|grep "$command" > /dev/null
if [ $? != 0 ]; then echo "没有发现进程，正在启动进程..."
$command > /dev/null 2>&1 &
ps aux|grep -v grep|grep "$command" > /dev/null
if [ $? = 0 ]; then echo -e "\032进程启动成功！\032[0m";else echo -e "\031进程启动失败！\031[0m";fi
else echo "进程已经存在，不需要重新启动进程！";fi