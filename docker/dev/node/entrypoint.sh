#!/bin/sh
uid=$(stat -c %u /srv/app)
gid=$(stat -c %g /srv/app)
if [ "$uid" -eq 0 ] && [ "$gid" -eq 0 ]; then
    if [ $# -eq 0 ]; then
        sleep 9999d
    else
        exec "$@"
    fi
fi
sed -i -r "s/node:x:1000:1000:Linux/node:x:100:100:Linux/g" /etc/passwd
sed -i -r "s/foo:x:\d+:\d+:/foo:x:$uid:$gid:/g" /etc/passwd
sed -i -r "s/bar:x:\d+:/bar:x:$gid:/g" /etc/group
chown foo /home
if [ $# -eq 0 ]; then
    sleep 9999d
else
    exec su-exec foo "$@"
fi
