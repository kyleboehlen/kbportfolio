#!/usr/bin/env bash

# Install Node
sudo yum install -y gcc-c++ make
yum --enablerepo=extras install -y epel-release
curl --silent --location https://rpm.nodesource.com/setup_18.x | bash -
sudo yum install -y nodejs --enablerepo=nodesource
