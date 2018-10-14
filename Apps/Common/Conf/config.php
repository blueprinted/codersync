<?php
return array(
    //'配置项'=>'配置值'
    'LOAD_EXT_CONFIG'       => 'basic',
    'DIR_SEP'               => DIRECTORY_SEPARATOR, // 目录分隔符
    'MULTI_MODULE'          =>  true, // 是否允许多模块 如果为false 则必须设置 DEFAULT_MODULE
    'MODULE_DENY_LIST'      =>  array('Common','Runtime'),
    'DEFAULT_MODULE'        =>  'Home',  // 默认模块
    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名称

    'URL_CASE_INSENSITIVE'  =>  false,
    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    'URL_PATHINFO_DEPR'     =>  '/',    // PATHINFO模式下，各参数之间的分割符号

    'LOAD_EXT_FILE'         =>  'function.dirs,function.xml,function.admin,extend',

    // 语言设置
    'LANG_SWITCH_ON'        =>  true,   // 开启语言包功能
    'DEFAULT_LANG'          =>  'zh-cn', // 默认语言
    'LANG_LIST'             =>  'zh-cn,en-us,zh-tw',
    'LANG_AUTO_DETECT'      =>  false,

    // 布局设置
    'TMPL_ENGINE_TYPE'      => 'Think',     // 默认模板引擎 以下设置仅对使用Think模板引擎有效
    'TMPL_CACHFILE_SUFFIX'  =>  '.php',      // 默认模板缓存后缀
    'TMPL_DENY_FUNC_LIST'   =>  'echo,exit',    // 模板引擎禁用函数
    'TMPL_DENY_PHP'         =>  false, // 默认模板引擎是否禁用PHP原生代码
    'TMPL_L_DELIM'          =>  '<{',            // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'          =>  '}>',            // 模板引擎普通标签结束标记
    'TMPL_VAR_IDENTIFY'     =>  'array',     // 模板变量识别。留空自动判断,参数为'obj'则表示对象
    'TMPL_STRIP_SPACE'      =>  true,       // 是否去除模板文件里面的html空格与换行
    'TMPL_CACHE_ON'         =>  true,        // 是否开启模板编译缓存,设为false则每次都会重新编译
    'TMPL_CACHE_PREFIX'     =>  '',         // 模板缓存前缀标识，可以动态改变
    'TMPL_CACHE_TIME'       =>  0,         // 模板缓存有效期 0 为永久，(以数字为值，单位:秒)
    'TMPL_LAYOUT_ITEM'      =>  '{__CONTENT__}', // 布局模板的内容替换标识
    'LAYOUT_ON'             =>  false, // 是否启用布局
    'LAYOUT_NAME'           =>  'layout', // 当前布局名称 默认为layout
    'DEFAULT_THEME'         =>  '',
    'TMPL_DETECT_THEME'     =>  false,

    'ACTION_SUFFIX'         =>  'Action', // 操作方法后缀

    'URL_HTML_SUFFIX'       =>  'jsp',      // URL伪静态后缀设置

    'TOKEN_ON'              =>  true,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'            =>  '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'            =>  'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'           =>  true,  //令牌验证出错后是否重置令牌 默认为true

    'VAR_AJAX_SUBMIT'       =>  'inajax',  // 默认的AJAX提交变量

    'VAR_PAGE'              =>  'page',

    'COOKIE_EXPIRE'         =>  0,       // Cookie有效期
    'COOKIE_DOMAIN'         =>  'rsync.docker.ime.sogou',      // Cookie有效域名
    'COOKIE_PATH'           =>  '/',     // Cookie路径
    'COOKIE_PREFIX'         =>  '',      // Cookie前缀 避免冲突
    'COOKIE_SECURE'         =>  false,   // Cookie安全传输
    'COOKIE_HTTPONLY'       =>  '',      // Cookie httponly设置

    /* SESSION设置 */
    'SESSION_AUTO_START'    =>  true,    // 是否自动开启Session
    'SESSION_OPTIONS'       =>  array('expire'=>86400/2), // session 配置数组 支持type name id path expire domain 等参数
    'SESSION_TYPE'          =>  'Filer', // session hander类型 默认无需设置 除非扩展了session hander驱动 [Filer:自定义文件存储,Db:数据库存储,Memcache:memcache存储,Mysqli:mysqli存储]
    'SESSION_SAVE_PATH'     =>  './Data/_Session', // SESSION_TYPE=Filer时 session文件存储的目录
    'SESSION_PREFIX'        =>  '', // session 前缀

    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'toolsets',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'tss_',    // 数据库表前缀
    'DB_PARAMS'             =>  array(), // 数据库连接参数
    'DB_DEBUG'              =>  true, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO'           =>  '', // 指定从服务器序号

    'SESSION_PREFIX'        =>  '', // session 前缀
);
