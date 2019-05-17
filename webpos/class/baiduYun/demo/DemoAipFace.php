<?php

// 引入人脸检测Face SDK
require_once '../AipFace.php';

// 定义常量
const APP_ID = '你的 App ID';
const API_KEY = '你的 Api Key';
const SECRET_KEY = '你的 Secret Key';

// 初始化
$aipFace = new AipFace(APP_ID, API_KEY, SECRET_KEY);

//人脸检测 获取所有属性
// var_dump($aipFace->detect(file_get_contents('face.jpg'), array(
//     'face_fields' => 'age,beauty,expression,faceshape,gender,glasses,landmark,race,qualities',
// )));

//人脸比对
// var_dump($aipFace->match(array(
//     file_get_contents('face.jpg'),
//     file_get_contents('face.jpg'),
// )));

//人脸注册
// var_dump($aipFace->addUser(
//     '123', //人脸ID
//     'angela babay', //人脸信息
//     'women', //分组
//     file_get_contents('face.jpg') //人脸
// ));

//人脸认证
// var_dump($aipFace->verifyUser(
//     '123', //人脸ID
//     'women', //分组
//     file_get_contents('face.jpg') //人脸
// ));

//人脸识别
// var_dump($aipFace->identifyUser(
//     'girls', //分组
//     file_get_contents('face.jpg') //人脸
// ));

//人脸更新
// var_dump($aipFace->updateUser(
//     '123', //人脸ID
//     'angela babay', //人脸信息
//     'women', //分组
//     file_get_contents('face.jpg') //人脸
// ));

//人脸删除
// var_dump($aipFace->deleteUser(
//     '123' //人脸ID
// ));

//人脸组内删除
// var_dump($aipFace->deleteGroupUser(
//     array('women', 'girls'), //分组
//     '123' //人脸ID
// ));

//人脸组内用户添加
// var_dump($aipFace->addGroupUser(
//     'women', //从这个分组
//     'girls', //到这个分组
//     '123' //复制这个ID的用户
// ));

//人脸ID信息查询
// var_dump($aipFace->getUser(
//     '123' //用户ID
// ));

//分组信息查询
// var_dump($aipFace->getGroupList());

//组内用户列表查询
// var_dump($aipFace->getGroupUsers(
//     'women'
// ));
