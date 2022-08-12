#!/usr/bin/env bash

# Install Node
sudo yum install -y gcc-c++ make
curl --silent --location https://rpm.nodesource.com/setup_16.x | bash -
sudo yum install -y nodejs --enablerepo=nodesource