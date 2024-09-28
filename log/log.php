<?php
ini_set("display_errors",env("LOG_DISPLAY",true));
ini_set("log_errors",env("LOG_FILE_CREATE",true));
ini_set("error_log",$path."\\log\\log_errors.log");