<?php

require_once "config.php";

class Logger {
	
	static  $logFileHandle;
	static $logFileName = 'c:\users\satyen\desktop\travelmarket-php-log';

	static function initLogger() {
		
		// open the logfile  
		
		if(isset(self::$logFileHandle))  {
			return;
		}
		
		if (file_exists(self::$logFileName)){
			//unlink(self::$logFileName);S
		}
		
		self::$logFileHandle = fopen(self::$logFileName, "w")  
			or die ("failed to open self::$logFileName"); 
			
		// this close will truncate the file
		// fclose(self::$logFileHandle);
		
		// self::$logFileHandle = fopen(self::$logFileName, "wt") ; 
	}

	public static function logMsg($fileName, $funcName, $msg) {
		if(!isset(self::$logFileHandle)) {
			self::initLogger();
		}
		fwrite(self::$logFileHandle,  basename($fileName)."  : $funcName : $msg\r\n");
		fflush(self::$logFileHandle);
	}
        
        public static function logTxt($msg) {
		if(!isset(self::$logFileHandle)) {
			self::initLogger();
		}
		fwrite(self::$logFileHandle, $msg."\r\n");
		fflush(self::$logFileHandle);
	}
	
	public static function logEntry($fileName, $funcName) {
		if(!isset(self::$logFileHandle)) {
			self::initLogger();
		}

		fwrite(self::$logFileHandle, "ENTER: ".basename($fileName) .": $funcName\n");
		fflush(self::$logFileHandle);
	}
	
	public static function logExit($fileName, $funcName, $sts) {
		if(!isset(self::$logFileHandle)) {
			self::initLogger();
		}
		fwrite(self::$logFileHandle, "EXIT: ".basename($fileName)." : $funcName : "."$sts\n");
		fflush(self::$logFileHandle);
	}
}

?>