<?php

    class Log {

        static private $abs_path;

        public function __construct ($logs_dir, $log_name) {
            //  Concat absolute path and create file if necessary.
			date_default_timezone_set('Europe/Stockholm');
			$date = date('Y-m-d');
			self::$path = __DIR__.'/logs/'.$date.'_'.$file_name.'.txt';
			if (!file_exists(dirname(self::$path))) {
                try { mkdir(dirname(self::$path)); }
                catch (Exception $e)
                { self::logException($e); }
            }
        }

        public function writeLine ($line_str) {
            if (!is_string($line_str)) {
                $msg = 'writeLine($line_str): Parameter must be of type string.';
                throw new InvalidArgumentException($msg); 
            }
        }

    }