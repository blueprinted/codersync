<?php
//如果没有任何配置 需要返回空数组

return array(
    'APP_GROUP' => array(
        'shouji' => array(
            'id' => 1,
            'group_name' => '手机组',
            'group_code' => 'pc',
            'group_config_file' => APP_PATH.MODULE_NAME.'/Conf/config.shouji.php',//额外的配置文件
            'app_list_file' => APP_PATH.MODULE_NAME.'/Conf/applist_shouji.php',//app列表配置
            'docker_deploy_repository' => 'git',
            'docker_deploy_repo_url' => 'https://git.sogou-inc.com/iweb/docker-deploy.git',
            'docker_deploy_dir' => '/search/odin/rsyncweb/docker-deploy',
            'docker_deploy_command_path' => '/usr/local/bin/odinctl2',
        ),
    ),
);
