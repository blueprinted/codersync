<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no" />
  <title>Api说明</title>
</head>
<body>
<pre>
代码更新(svn update)接口：

地址：http://cms.shouji.sogou-inc.com/toolsets/rsync/api/update

请求方式：POST

接收参数：
[1]group 分组名 ['shouji','pc','qq','srv'] 缺省 空字符串
[2]appid 项目id 缺省 0
[3]istest 是否测试环境 [0:正式环境,1:测试环境] 缺省0
[4]ver 更新到的版本号 缺省0
[5]dir 更新的目录或文件(相对于项目根目录) 支持传数组(用http_build_query组织)
  dir示例：项目根目录是 /search/rsyncweb/shouji.sogou.com/  如果要更新 /search/rsyncweb/shouji.sogou.com/index.php 这个文件，则 dir参数传 index.php

参数值需要urlencode

返回数据示例(json)：
{
data: {
cmds: [ ],
lines: [ ]
},
info: "目录或文件为空",
status: 1
}

说明：
状态码是0表示更新成功，其他情况更新失败。

@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

代码发布(rsync)接口：

地址：http://cms.shouji.sogou-inc.com/toolsets/rsync/api/rsync

请求方式：POST

接收参数：
[1]group 分组名 ['shouji','pc','qq','srv'] 缺省 空字符串
[2]appid 项目id 缺省 0
[3]istest 是否测试环境 [0:正式环境,1:测试环境] 缺省0
[4]dir 更新的目录或文件(相对于项目根目录) 支持传数组(用http_build_query组织)
  dir示例同 update 接口。

参数值需要urlencode

返回数据示例(json)：
{
data: {
cmds: [ ],
lines: [ ],
files: [ ]
},
info: "目录或文件为空",
status: 1
}

说明：
状态码是0表示发布成功，其他情况发布失败。
</pre>
</body>
</html>
