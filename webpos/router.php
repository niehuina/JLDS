<?php
header_utf8();

router::all('/','home/login@index');
router::all('login','doc/login@index');

 