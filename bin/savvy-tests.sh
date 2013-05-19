#!/bin/bash

cd "`dirname $0`/../tests" && phpunit
cd - >/dev/null 2>&1
