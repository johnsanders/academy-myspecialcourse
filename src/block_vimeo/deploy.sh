#!/bin/bash

/bin/rm ./.DS_Store
cd ../

/usr/bin/tar \
	--exclude='./.git*' \
	--exclude='*.DS_Store' \
	-czvf vimeo.tar.gz ./vimeo
/usr/bin/scp vimeo.tar.gz cnnitouch@academy.cnntoolbox.com:/home/cnnitouch

/bin/rm ./vimeo.tar.gz

/usr/bin/ssh -tt cnnitouch@academy.cnntoolbox.com << EOF
/usr/bin/sudo bash
cd /tmp
cd /var/lib/docker/volumes/server_moodle_data/_data/mod
/bin/rm -rf vimeo
/bin/mv /home/cnnitouch/vimeo.tar.gz ./
/bin/tar -xzvf vimeo.tar.gz
/bin/rm vimeo.tar.gz
exit
exit
EOF
