#!/bin/bash

srcdir="`dirname $0`/../src"

doctrine orm:clear-cache:metadata
doctrine orm:generate-entities "${srcdir}"
doctrine orm:generate-proxies
doctrine orm:schema-tool:update --complete --force
doctrine dbal:import "${srcdir}/Savvy/Storage/Schema/Base.sql"
