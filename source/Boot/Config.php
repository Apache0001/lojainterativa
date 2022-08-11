<?php

################
### DATABASE ###
################
define("CONF_DB_HOST", "localhost");
define("CONF_DB_NAME", "lojainterativa");
define("CONF_DB_USER","root");
define("CONF_DB_PASSWD", "");


####################
### PROJECT URLs ###
####################
define("CONF_URL_BASE", "http://localhost/projeto");
define("CONF_URL_TEST", "http://localhost/projeto");
define("CONF_URL_ADMIN", "/admin");

############
### VIEW ###
############
define('CONF_VIEW_PATH', __DIR__.'/../../themes');
define('CONF_VIEW_EXT', 'php');
define('CONF_VIEW_THEME_ADMIN', 'Admin');
define('CONF_VIEW_THEME', 'Web');
define('CONF_VIEW_THEME_DASH','Dash');


##############
### UPLOAD ###
##############
define("CONF_UPLOAD_DIR", "storage");
define("CONF_UPLOAD_IMAGE_DIR", "images");
define("CONF_UPLOAD_FILE_DIR", "files");
define("CONF_UPLOAD_MEDIA_DIR", "medias");


#############
### DATES ###
#############
define("CONF_DATE_BR", "d/m/Y H:i:s");
define("CONF_DATE_APP", "Y-m-d H:i:s");


###############
### MESSAGE ###
###############
define("CONF_MESSAGE_WARNING",'warning');
define("CONF_MESSAGE_SUCCESS",'success');
define("CONF_MESSAGE_ERROR", 'error');
define('CONF_MESSAGE_CLASS', 'message');
define("CONF_MESSAGE_INFO", 'info');

################
### PASSWORD ###
################
define("CONF_PASSWD_MIN_LEN", 8);
define("CONF_PASSWD_MAX_LEN", 40);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTION", ["cost" => 10]);