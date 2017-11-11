<?php

    /**
     * Handles the content entries in the database.
     */
    class Contents extends ContentsModel {

        /**
         * Initiates the class
         */
        public function __construct () {

        }

        /**
         * Returns several entries with the same marker.
         *
         * @param string $marker
         * @return object
         */
        public function getByMarker ($marker) {

        }

        /**
         * Gets a specifik entry from the database.
         *
         * @param integer $id
         * @return object
         */
        public function getById ($id) {

        }

        /**
         * Returns a spresific entry as raw markdown.
         *
         * @param ingeter $id
         * @return object
         */
        public function getAsMd ($id) {

        }

        /**
         * Adds a new entry to the databse.
         *
         * @param object $data
         * @return boolean
         */
        public function newEntry ($data) {

        }

        /**
         * Updates the entry in the database.
         *
         * @param object $data
         * @return boolean
         */
        public function updateEntry ($data) {

        }

        /**
         * Moves an entry from contents to archive.
         *
         * @param object $data
         * @return boolean
         */
        public function archiveEntry ($data) {

        }

    }
?>