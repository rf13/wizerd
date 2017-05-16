#!/bin/bash

git checkout master
git merge ui-updates --no-edit
git push
git checkout ui-updates
