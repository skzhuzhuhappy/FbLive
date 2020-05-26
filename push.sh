#!/bin/sh
echo " ====开始推送本地最新代码==== "
git add .
git commit -am '最新代码'
git push origin master;
git status;
echo "  "
