#!/bin/bash

# start mysql
/etc/init.d/mysql start

# run apache in foreground
apachectl -D FOREGROUND
