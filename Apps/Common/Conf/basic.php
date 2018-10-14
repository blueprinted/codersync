<?php
return array(
    'AUTHCODE' => 'toolsets~!@#$%^&*()',
    'PAGE_LISTROWS' => 50,
    'WAPDL_URL' => 'http://wap.dl.pinyin.sogou.com/wapdl/',//以 / 结尾
    'WANGSU_URL' => 'http://img.shouji.sogou.com/wapdl/',//以 / 结尾
    'PANDORA_APPID' => $_SERVER['HTTP_HOST'] == 'cms.shouji.sogou-inc.com' ? 1079 : ($_SERVER['HTTP_HOST'] == 'svn.ime.sogou' ? 1081 : ($_SERVER['HTTP_HOST'] == 'rsync.docker.ime.sogou' ? 1486 : 0)),
    'PANDORA_PEMFILE' => realpath(APP_PATH).'/Common/Conf/'.($_SERVER['HTTP_HOST'] == 'cms.shouji.sogou-inc.com' ? 'cms.shouji.sogou-inc.com.public.pem' : ($_SERVER['HTTP_HOST'] == 'svn.ime.sogou' ? 'svn.ime.sogou.public.pem' : ($_SERVER['HTTP_HOST'] == 'rsync.docker.ime.sogou' ? 'rsync.docker.ime.sogou.public.pem' : ''))),//公钥文件地址
);
