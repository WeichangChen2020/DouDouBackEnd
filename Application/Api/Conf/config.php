<?php
/**
 * Created by PhpStorm.
 * User: lijunjun
 * Date: 2018/1/27
 * Time: 上午12:14
 */

return array(

    'APP_NAME' => 'pingshifen', 'API_LIST' => ['SCHOOL', 'STUDENT', 'WEIXIN', 'TEACHER', 'MY', 'COURSE', 'SIGNIN', 'QUESTION'],

    /* 数据库配置 */
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '127.0.0.1', // 服务器地址
    'DB_NAME' => 'pingshifen', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => '',  // 密码
    'DB_PORT' => '3306', // 端口
    'DB_PREFIX' => 'pingshifen_', // 数据库表前缀

    'DB_PARAMS' => array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL), // 数据库兼容大小写

    /* 文件上传路径 */
    'APP_ROOT' => 'http://127.0.0.1/pingshifen/', 'UPLOAD_DIR' => 'Uploads/Picture/', 'UPLOAD_ROOT' => 'http://127.0.0.1/pingshifen/Uploads/Picture/',


);