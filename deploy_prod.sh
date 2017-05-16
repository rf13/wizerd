#!/bin/bash
git checkout master
git merge ui-updates
git push
git checkout ui-updates
ssh owner@208.94.247.74 "cd /home/owner/html; git pull"
