<?php

//Basics of config file
define('MODULE_SSPAYPALBASIC_DIR', basename(dirname(__FILE__)));

DataObject::add_extension('SiteTree', 'SSPaypalBasic');
