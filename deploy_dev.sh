#!/bin/bash
git push
ssh owner@208.94.247.74 "cd /home/owner/dev/html; git pull"
