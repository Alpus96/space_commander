<?php

    class ContentsModel extends Mysql {

        static private $query;

        protected function __construct () {
            parent::__construct();
            self::$query = (object)[
                'insert' => ''
            ];
        }

        protected function insert ($marker, $text, $author) {

        }

        protected function selectMarker ($marker, $offset, $amount) {

        }

        protected function selectId ($id) {

        }

        protected function update ($id, $text) {

        }

        protected function moveToArchive ($id) {

        }
        
    }
?>