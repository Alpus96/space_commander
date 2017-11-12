<?php

    class Log {

        static private $abs_path;

        public function __construct ($logs_dir, $log_name) {
            //  Concat absolute path and create file.
        }

        public function writeLine ($line_str) {
            if (!is_string($line_str)) { throw new InvalidArgumentException('writeLine($line_str): Parameter must be of type string.'); }
        }

    }