<?php
/**
 *  app配置
 *  2018-10-10 01:10:55
 */

return array (
  'APP_LIST' =>
  array (
    0 =>
    array (
      'id' => 0, //key值与id值需要一致
      'name' => '手机开发工具集合', //项目的名称
      'tips' => 'http://cms.shouji.sogou-inc.com/toolsets/', //项目的附加信息
      'link' => 'http://cms.shouji.sogou-inc.com/toolsets/', //项目的前端正式地址,没有请留空
      'link_test' => '', //项目的前端测试地址,没有请留空
      'root_dir' => '/search/odin/rsyncweb/shouji/toolsets', //项目的根目录,也是代码仓库检出到的目录,结尾不要带 /
      'root_dir_test' => '', //项目的根目录[测试环境],也是代码仓库检出到的目录,结尾不要带 /
      'repository' => 'svn', //仓库类型 svn / git
      'repo_is_sogou' => true, //标识是否为sogou的托管仓库
      'repo_auth' => true, //仓库是否需要账户认证
      'repo_url' => 'http://svn.sogou-inc.com/svn/iweb/sogoushouji/toolsets/test', //托管仓库的地址
      'repo_username' => C('SOGOUSVN_USERNAME_DEFAULT'), //托管仓库的账户名
      'repo_password' => C('SOGOUSVN_PASSWORD_DEFAULT'), //托管仓库的账户密码
      'repo_auth_test' => true, //仓库是否需要账户认证[测试环境]
      'repo_url_test' => '', //托管仓库的地址[测试环境]
      'repo_username_test' => '', //托管仓库的账户名[测试环境]
      'repo_password_test' => '', //托管仓库的账户密码[测试环境]
      'repo_bin_path' => '', //仓库命令的二进制路径 缺省会使用 /usr/bin/svn 或 /usr/bin/git
      'is_sogou_docker' => false, //是否为sogou的docker项目
      'docker_deploy_file' => '', //docker项目的deploy配置文件 如 srv-coupon.json 或 srv-coupon.yaml
      'docker_deploy_cfg' => '', //docker项目的deploy其他配置 用英文半角逗号分隔 含义 正式目录,测试目录,正式环境镜像所处节点的路径及name:value,测试环境镜像所处节点的路径及name:value 缺省值 mercury,test,containers:name=gobin,containers:name=gobin
      'use_roc_node' => false, //是否使用roc节点方式获取机器ip列表
      'roc_sid' => 0, //对应的roc节点id 支持填写多个, 用英文半角逗号分隔即可
      'use_roc_node_test' => false, //是否使用roc节点方式获取机器ip列表[测试环境]
      'roc_sid_test' => 0, //对应的roc节点id[测试环境]
      'rsync_shell' => realpath(APP_PATH.MODULE_NAME).'/Shell/shouji/rsync_toolsets.sh', //rsync发布脚本的完整路径
      'rsync_shell_test' => '', //rsync发布脚本的完整路径[测试环境]
      'syntax_check' => true, //是否对文件进行语法检查
      'syntax_check_test' => true, //是否对文件进行语法检查[测试环境]
      'syntax_check_bin_path' => '', //语法检查命令的的二进制路径,缺省为php
      'syntax_check_file_exts' => '', //要进行语法检查的文件名后缀(多个后缀请使用英文半角逗号进行分隔),留空则默认为php,如:php 或者 php,go 或者 php,java,go
      'code_standards_check' => true, //对要发布(发布到正式环境)的文件是否执行代码规范检查
      'code_standards_check_test' => true, //对要发布(发布到测试环境)的文件是否执行代码规范检查
      'code_standards_check_file_exts' => 'php', //要进行代码规范检查的文件名后缀(多个后缀请使用英文半角逗号进行分割),留空则默认为php,如:php 或者 php,go 或者 php,java,go
      'code_standards_check_bin_path' => '/opt/rh/rh-php71/root/bin/php /usr/bin/phpcs -s --standard='.realpath(APP_PATH.MODULE_NAME).'/Conf/code_standards/ruleset.xml -s {filepath}', //执行代码规范检查命令的执行路径,缺省为空
      'restart_server' => false, //正式环境代码发布后是否需要重启服务
      'restart_shell' => '', //正式环境重启服务的shell文件路径
      'restart_server_test' => false, //测试环境代码发布后是否需要重启服务
      'restart_shell_test' => '', //测试环境重启服务的shell文件路径
      'sogou_sac' => false, //是否为搜狗sac项目
      'sac_appid' => 0, //sac对应的appid
      'sac_app_dir' => '', //sac项目对应的模板目录
      'export_single_confirm' => false, //是否询问单个文件的导出
      'export_multi_confirm' => true, //是否询问多个文件的导出
      'update_single_confirm' => false, //是否询问单个文件的更新
      'update_multi_confirm' => true, //是否询问多个文件的更新
      'update_vn_confirm' => true, //是否询问按版本号的更新
      'rsync_single_confirm' => false, //是否询问单个文件的发布
      'rsync_multi_confirm' => true, //是否询问多个文件的发布
      'export_single_confirm_test' => false, //是否询问单个文件的导出[测试环境]
      'update_multi_confirm_test' => true, //是否询问多个文件的导出[测试环境]
      'update_vn_confirm_test' => false, //是否询问按版本号的更新[测试环境]
      'update_single_confirm_test' => false, //是否询问单个文件的更新[测试环境]
      'export_multi_confirm_test' => true, //是否询问多个文件的更新[测试环境]
      'rsync_single_confirm_test' => false, //是否询问单个文件的发布[测试环境]
      'rsync_multi_confirm_test' => true, //是否询问多个文件的发布[测试环境]
    ),
  ),
);
