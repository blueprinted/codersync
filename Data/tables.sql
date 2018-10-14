
--
-- 数据表
--

--
-- 通用字段说明
-- create_time 创建时间或行为产生的时间
-- update_time 更新时间
--

--
-- 会员表
--
CREATE TABLE IF NOT EXISTS `tss_user` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(64) NOT NULL,
  `nickname` varchar(32) NOT NULL,
  `realname` varchar(32) NOT NULL,
  `unumber` varchar(16) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` char(32) NOT NULL,
  `gender` tinyint(1) NOT NULL default '0',
  `birthday` char(10) NOT NULL,
  `regip` char(15) NOT NULL,
  `ips` varchar(64) NOT NULL,
  `salt` char(8) NOT NULL,
  `last_ip` char(15) NOT NULL,
  `last_time` int(10) unsigned NOT NULL default '0',
  `avatar` varchar(255) NOT NULL,
  `signature` varchar(255) NOT NULL,
  `siteurl` varchar(255) NOT NULL,
  `utype` smallint(1) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `create_time` int(10) unsigned NOT NULL default '0',
  `update_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`,`create_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
--
--uid 会员自增id
--username 账号名
--nickname 昵称
--email 电子邮箱
--gender 性别 0:未知, 1:男, 2:女
--regip 注册ip
--ips 注册全ip(含代理)
--salt 混淆串
--avatar 用户头像地址
--utype 用户类型 [0:普通用户,1管理员]
--status 用户状态 [0:禁用,1:正常,2:未验证]
--last_ip 最后一次登录ip
--last_time 最后一次登录的时间
--


--
-- 插件机制 start
--

CREATE TABLE IF NOT EXISTS `tss_plugins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `type` tinyint(2) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `config` text NOT NULL,
  `hooks` varchar(255) NOT NULL,
  `has_admin` tinyint(2) NOT NULL DEFAULT '0',
  `author` varchar(50) NOT NULL,
  `version` varchar(20) NOT NULL,
  `listorder` smallint(6) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
--id 自增id
--name 插件名[英文]
--title 插件名称
--description 插件描述
--type 插件类型 [1:网站,8;微信]
--status 状态 [1:启用,0:停用]
--config 插件配置
--hooks 实现的钩子 以,进行分隔
--has_admin 插件是否有后台管理界面
--author 插件作者
--version 插件版本号
--listorder 列表排序值
--

--
-- 插件机制 end
--

--
-- RBAC[Role-Based Access Control] 基于角色访问控制 start
--

--
-- 加上前面的user表
--

--
-- 角色表
--
CREATE TABLE IF NOT EXISTS `tss_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL,
  `listorder` int(3) NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
--
--id 自增id
--name 角色名称
--pid 父角色ID
--status 状态
--remark 备注
--listorder 列表排序值
--

--
-- 用户角色关系表
--
CREATE TABLE IF NOT EXISTS `tss_role_user` (
  `role_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  KEY `role_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
--role_id 角色id
--user_id 用户id
--

--
-- 权限规则表
--
CREATE TABLE IF NOT EXISTS `tss_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(32) NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `param` varchar(255) NOT NULL,
  `title` varchar(32) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` varchar(300) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
--
--id 权限规则id,自增主键
--module 规则所属module
--type 权限规则分类，请加应用前缀,如admin_
--name 规则唯一英文标识,全小写
--param 额外url参数
--title 规则的名称(或标题)
--status 是否有效[0:无效,1:有效]
--condition 规则附加条件
--

--
-- 权限授权表
--
CREATE TABLE IF NOT EXISTS `tss_auth_access` (
  `role_id` mediumint(8) unsigned NOT NULL,
  `rule_name` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL,
  KEY `role_id` (`role_id`),
  KEY `rule_name` (`rule_name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
--role_id 角色id
--rule_name 规则唯一英文标识,全小写
--type 权限规则分类，请加应用前缀,如admin_
--

--
-- 菜单表
--
CREATE TABLE IF NOT EXISTS `tss_menu` (
  `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parentid` smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  `app` char(20) NOT NULL,
  `model` char(20) NOT NULL,
  `action` char(20) NOT NULL,
  `data` char(50) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `listorder` smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `parentid` (`parentid`),
  KEY `model` (`model`),
  KEY `create_time` (`create_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
--id 菜单自增id
--parentid 父级id
--app 应用名称app
--model 控制器
--action 操作名称
--data 额外参数
--type 菜单类型  1：权限认证+菜单；0：只作为菜单
--status 状态，1显示，0不显示
--name 菜单名称
--icon 菜单图标
--remark 备注
--listorder 列表排序值
--

--
--  发布系统相关数据表 start
--

--
--  发布流程表
--
CREATE TABLE IF NOT EXISTS `tss_rsync_deploy` (
  `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `appid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `groupid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `username` varchar(64) NOT NULL,
  `pack_taskid` char(32) NOT NULL,
  `pack_revision` char(32) NOT NULL,
  `pack_info` mediumtext NOT NULL,
  `back_taskid` char(32) NOT NULL,
  `back_revision` char(32) NOT NULL,
  `back_info` mediumtext NOT NULL,
  `istest` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL default '0',
  `locked` tinyint(1) NOT NULL default '0',
  `msglog` varchar(255) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `cmds` mediumtext NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `deploy_time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `rollback_time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
--id 自增ID
--appid 项目ID
--groupid 分组ID
--uid 发起上线流程的uid
--username 发起上线流程的username
--pack_taskid pack的打包任务id
--pack_revision pack的修订值 相当于git的commit的hash值
--pack_info 对应本次上线的harbor仓库的镜像信息 托管在git且是sogoudocker的项目用到这个字段 存放json_encode数据
--back_taskid back的打包任务id
--back_revision back的修订值 相当于git的commit的hash值
--back_info 对应本次回滚的harbor仓库的镜像信息 托管在git且是sogoudocker的项目用到这个字段 存放json_encode数据
--istest 是否为测试环境
--status 上线流程进度状态 0:待发版 1:已发版 2:已回滚
--locked 是否已经锁定 0:未锁定 1:已锁定 锁定之后不能进行任何操作（包含更新，发版，回滚）
--msglog 本次上线的日志（记录功能改动信息）
--remark 附加备注信息
--cmds 执行的cmd信息 存放json_encode数据
--deploy_time 发布时间
--rollback_time 回滚时间
--

--
--  发布流程锁管理表
--
CREATE TABLE IF NOT EXISTS `tss_rsync_locker` (
  `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lockkey` varchar(64) NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `username` varchar(64) NOT NULL,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lockkey` (`lockkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
--id 自增ID
--lockkey 锁的唯一名称
--uid
--username
--update_time 更新时间
--

--  发布系统相关数据表 end
--
