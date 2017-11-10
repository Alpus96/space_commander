<?php

    class User extends UserModel {

        public function __construct ($token) {
            parent::__construct();
        }

        public function isActive () {

        }

        public function showName () {

        }

        public static function login ($credentials) {
            /**
             * @todo: parent not constructed if used static.
             */
        }

    }
?>