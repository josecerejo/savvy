#!/bin/bash

cd `dirname $0`

phpdoc run -d ../src -t ../public/doc/api --defaultpackagename=Savvy

cd - >/dev/null 2>&1
