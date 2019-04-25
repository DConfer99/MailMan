#!/bin/bash

#############################
# MailMan OS Check          #
# Author: Brennan Doherty   #
# Date: April 24th 2019     #
#############################

###########################################################
#Description:
#Checks the host operating system to see if MailMan can run.
#Currently we only support Ubuntu 16.04 and 18.04.
#Future support for CentOS 7 will be implemented.
###########################################################

#If OS does not support uname this should be null
os_type=`uname`

if [[ $os_type = "Linux" ]]; then
  #Currently only support Ubuntu
  #Should also be null if OS does not support
  if [[ `lsb_release -i` = "*Ubuntu*" ]]; then
    echo "This is Ubuntu"
  fi
else
  echo "unsupported"
fi
