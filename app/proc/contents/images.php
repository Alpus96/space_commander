<?php

    /**
     * Helps handling images on the server.
     */
    class Image {

        private static $lib_path;

        /**
         * Initiates the class by setting the image library path.
         *
         * @param string $lib_path
         */
        public function __construct ($lib_path) {
            self::$lib_path = $lib_path;
        }

        /**
         * @todo How to ???
         */
        public function save ($data) {

        }

        /**
         * Deletes an imagefile.
         *
         * @param object $data
         * @return boolean
         */
        public function delete ($data) {

        }

    }
?>