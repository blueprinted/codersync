#!/bin/bash

cd <{docker_deploy_dir}>
<{expect_bin_path}> <<EOF
set retval 0
spawn <{docker_deploy_command_path}> login
expect "*name*" {send "<{username}>\n"}
expect "*password*" {send "<{password}>\n"}
expect {
"*login failed*" {set retval 1}
"*login success*" {set retval 0}
}
if { \$retval != 0 } {
    exit \$retval
}
set retval 0
spawn <{docker_deploy_command_path}> apply -f <{docker_deploy_file}>
expect {
"*err*" {set retval 2}
"*apply complete*" {set retval 0}
}
exit \$retval
expect eof
EOF

# 执行成功的输出
# [@tc_56_211 test]$ cd /search/odin/rsyncweb/docker-deploy/test && /usr/local/bin/odinctl2 apply -f srv-expression.json
# apply application
# apply complete
# [@tc_56_211 test]$

# 执行失败的输出
# [@tc_56_211 html]$ cd /search/odin/rsyncweb/docker-deploy/test && /usr/local/bin/odinctl2 apply -f srv-expression1.json
# get content err: read file srv-expression1.json failed, file must be in the current directory
# [@tc_56_211 test]$
