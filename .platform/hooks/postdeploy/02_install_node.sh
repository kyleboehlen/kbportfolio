#!/usr/bin/env bash

# Install Node
sudo yum install -y gcc-c++ make
curl --silent --location https://rpm.nodesource.com/setup_18.x | bash -
sudo dnf install nodejs -y
sudo yum install npm -y
