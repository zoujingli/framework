#!/usr/bin/env bash

command="php `pwd`/think queue:listen"

if [ `ps aux|grep -v grep|grep "$command"|wc -l` = 0 ]
then
echo 需要启动命令 $command
$command &
else
echo 不需要启动命令 $command
fi
