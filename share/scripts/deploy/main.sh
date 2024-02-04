#!/bin/bash

# ALLOW LAN CONNECTION FOR GLUSTER
sudo iptables -A INPUT -s 192.168.0.0/24 -j ACCEPT
sudo iptables -A OUTPUT -s 192.168.0.0/24 -j ACCEPT


## CONSIDER CONCAT OF /etc/hosts FILE WITH DNS ROUND-ROBIN FOR gluster.suitespace.corp AND/OR INTERNAL DNS SERVER(S) OR avahi


# Install GlusterFS dependencies and utilities

sudo dnf install tmux -y
sudo dnf install zsh -y
sudo dnf install centos-release-gluster -y
sudo dnf config-manager --set-enabled PowerTools
sudo dnf install openssh-server -y
sudo dnf install wget -y
sudo dnf install opensm -y
sudo dnf install libibverbs -y
sudo dnf install fuse -y
sudo dnf install fuse-libs -y
sudo dnf install glusterfs -y
sudo dnf install glusterfs-fuse -y
sudo dnf install glusterfs-api -y
sudo dnf install glusterfs-cli -y
sudo dnf install glusterfs-rdma -y
sudo dnf install glusterfs-libs -y
sudo dnf install glusterfs-cloudsync-plugins -y
sudo dnf install glusterfs-client-xlators -y

# Make a directory for shared files, set owner to default user "centos"
sudo mkdir -p /usr/share/suitespace && sudo chown -R centos:centos /usr/share/suitespace

# Mount the SuiteShare Gluster Cluster 
sudo mount -t glusterfs -o acl 192.168.0.251:/SuiteShare /usr/share/suitespace

#Add SuiteShare to File System Table /etc/fstab after checking that it is not there
sudo grep -q 'SuiteShare Gluster Cluster' /etc/fstab || 
sudo printf '# SuiteShare Gluster Cluster\n192.168.0.251:/SuiteShare /usr/share/suitespace    ext4    glusterfs defaults,acl,_netdev 0 0\n' >> /etc/fstab


# Import Our Users
sudo newusers /usr/share/suitespace/config/corporate_users
