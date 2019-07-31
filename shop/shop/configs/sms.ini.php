<?php

$sms_config = array();

$sms_config['sms_url'] = 'http://39.104.115.26/smsJson.aspx';
$sms_config['sms_account'] = 'ycfswh';
$sms_config['sms_pass'] = 'ycfswh@2019';
$sms_config['sms_signature'] = '【一次方商城】';

Yf_Registry::set('sms_config', $sms_config);

return $sms_config;
?>