<?php

/**
 *  实用工具
 *  zouchao@sogou-inc.com
 *  2018-10-09
 */

namespace Rsync\Controller;

use Think\Controller;

class ToolController extends Controller
{
    /**
     * 发布系统支持git+docker后重新定义了项目配置文件，需要重写项目配置文件，该控制器用于实现配置文件重写
     * 2018-10-09
     */
    public function convertConfigAction()
    {
        $dir = realpath(APP_PATH.MODULE_NAME) . '/Conf';
        $files = array(
          "{$dir}/applist.php",
          "{$dir}/applist_pc.php",
          "{$dir}/applist_qq.php",
          "{$dir}/applist_shouji.php",
          "{$dir}/applist_srv.php",
        );

        $item_template = array(
            'id' => null,
            'name' => null,
            'tips' => null,
            'link' => null,//项目的前端正式地址,没有请留空
            'link_test' => null,//项目的前端测试地址,没有请留空
            'root_dir' => null,//项目的根目录,也是仓库导出到的目录,结尾不要带 /
            'root_dir_test' => null,//项目的根目录[测试环境],也是仓库导出到的目录,结尾不要带 /
            'repository' => null, //svn 或 git 缺省或不填时默认为 svn
            'repo_is_sogou' => null,//标识是否为sogou自建的托管仓库
            'repo_auth' => null,//托管仓库是否需要认证
            'repo_url' => null,//
            'repo_username' => null,//仓库的账号
            'repo_password' => null,//仓库的密码
            'repo_auth_test' => null,//测试环境仓库是否需要认证
            'repo_url_test' => null,//测试环境仓库的url地址 结尾带 /
            'repo_username_test' => null,//测试环境仓库的账号
            'repo_password_test' => null,//测试环境仓库的密码
            'repo_bin_path' => null,//仓库命令的路径 缺省且repository=svn时该值为svn 缺省且repository=git时该值为git 建议缺省
            'is_sogou_docker' => null,
            'docker_deploy_file' => null,
            'use_roc_node' => null,//是否使用roc节点获取机器ip列表
            'roc_sid' => null,//对应的roc节点id 支持配置多个roc_sid 如 '123,456'
            'use_roc_node_test' => null,//测试环境是否使用roc节点获取机器ip列表
            'roc_sid_test' => null,//测试环境对应的roc节点id 支持配置多个roc_sid 如 '123,456'
            'rsync_shell' => null,//rsync shell脚本
            'rsync_shell_test' => null,//rsync shell脚本[测试环境]
            'syntax_check' => null,//对要发布(发布到正式环境)的文件是否执行语法检查 发布系统所在服务器的软件(如php)版本跟线上机器或测试机器的软件版本不一致时可能需要关闭语法检查功能
            'syntax_check_test' => null,//对要发布(发布到测试环境)的文件是否执行语法检查
            'syntax_check_bin_path' => null,//执行语法检查命令的执行路径,缺省为php
            'sogou_sac' => null,//是否sac机器
            'sac_appid' => null,//sac的appid
            'sac_app_dir' => null,//sac的模板目录,结尾不要带 /
            'export_single_confirm' => null,//是否询问单个文件的导出
            'export_multi_confirm' => null,//是否询问多个文件的导出
            'update_single_confirm' => null,//是否询问单个文件的更新
            'update_multi_confirm' => null,//是否询问多个文件的更新
            'update_vn_confirm' => null,//是否询问按版本号的更新
            'rsync_single_confirm' => null,//是否询问单个文件的发布
            'rsync_multi_confirm' => null,//是否询问多个文件的发布
            'export_single_confirm_test' => null,//是否询问单个文件的导出[测试环境]
            'export_multi_confirm_test' => null,//是否询问多个文件的更新[测试环境]
            'update_single_confirm_test' => null,//是否询问单个文件的更新[测试环境]
            'update_multi_confirm_test' => null,//是否询问多个文件的导出[测试环境]
            'update_vn_confirm_test' => null,//是否询问按版本号的更新[测试环境]
            'rsync_single_confirm_test' => null,//是否询问单个文件的发布[测试环境]
            'rsync_multi_confirm_test' => null,//是否询问多个文件的发布[测试环境]
        );

        foreach ($files as $file) {
            $config = include $file;
            if (isset($config['APP_LIST'])) {
                $list = $config['APP_LIST'];
            } else {
                $list = $config['APP_LIST_GROUP'];
            }
            $list_new = array();
            foreach ($list as $item) {
                $item_new = array_merge($item_template, $item);
                $item_new = common_generate_conf_by_template($item_template, $item_new, $notsetkeys);
                foreach ($notsetkeys as $key) { //对缺的字段进行特殊处理
                    switch ($key) {
                        case '[repository]':
                            $item_new['repository'] = 'svn';
                            break;
                        case '[repo_is_sogou]':
                            $item_new['repo_is_sogou'] = $item['sogou_svn'];
                            break;
                        case '[repo_auth]':
                            $item_new['repo_auth'] = $item['svn_auth'];
                            break;
                        case '[repo_url]':
                            $item_new['repo_url'] = $item['svn_url'];
                            break;
                        case '[repo_username]':
                            $item_new['repo_username'] = '';
                            break;
                        case '[repo_password]':
                            $item_new['repo_password'] = '';
                            break;
                        case '[repo_auth_test]':
                            $item_new['repo_auth_test'] = $item['svn_test_auth'];
                            break;
                        case '[repo_url_test]':
                            $item_new['repo_url_test'] = $item['svn_test_url'];
                            break;
                        case '[repo_username_test]':
                            $item_new['repo_username_test'] = '';
                            break;
                        case '[repo_password_test]':
                            $item_new['repo_password_test'] = '';
                            break;
                        case '[repo_bin_path]':
                            $item_new['repo_bin_path'] = '';
                            break;
                        case '[is_sogou_docker]':
                            $item_new['is_sogou_docker'] = false;
                            break;
                        case '[docker_deploy_file]':
                            $item_new['docker_deploy_file'] = '';
                            break;
                        default:
                            ;
                    }
                }
                //交换 name 与 tips
                $temp = $item_new['name'];
                $item_new['name'] = $item_new['tips'];
                $item_new['tips'] = $temp;
                $list_new[$item_new['id']] = $item_new;
            }
            if (isset($config['APP_LIST'])) {
                $config_new = array(
                    'APP_LIST' => $list_new,
                );
            } else {
                $config_new = array(
                    'APP_LIST_GROUP' => $list_new,
                );
            }
            $filename = basename($file);
            $dirname = dirname($file);
            if (!file_exists("{$dirname}/convertConfig") && !mkdir("{$dirname}/convertConfig", 0775)) {
                echo "创建目录失败";
                exit;
            }
            $file_new = "{$dirname}/convertConfig/{$filename}";
            $groupname = '';
            if ($filename == 'applist.php') {
                $groupname = '';
            } elseif ($filename == 'applist_pc.php') {
                $groupname = 'PC组';
            } elseif ($filename == 'applist_qq.php') {
                $groupname = 'QQ组';
            } elseif ($filename == 'applist_shouji.php') {
                $groupname = '手机组';
            } elseif ($filename == 'applist_srv.php') {
                $groupname = '服务组';
            }
            $content = "<?php\n/**\n *  {$groupname}app配置\n *  ".date('Y-m-d H:i:s')."\n */\n\nreturn " . var_export($config_new, true) .";\n";
            if (false === file_put_contents($file_new, $content)) {
                echo "file convertConfig fail [{$file}]<br/>";
            }
        }

        echo "执行完毕";
    }
}
