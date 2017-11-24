<?php

    /**
     * Handles the content entries in the database.
     */
    class Contents extends ContentsModel {

        /**
         * Initiates the class
         */
        public function __construct () {
            parent::__construct();
        }

        /**
         * Returns several entries with the same marker.
         *
         * @param string $marker
         * @return object
         */
        public function getByMarker ($data) {
            if (!property_exists($data, 'marker')) { return false; }
            if (!property_exists($data, 'offset')) { return false; }
            if (!property_exists($data, 'amount')) { return false; }
            /**
             * @todo Add markdown parsing if needed.
             */
            return parent::selectMarker($data->marker, $data->offset, $data->amount);
        }

        /**
         * Gets a specifik entry from the database.
         *
         * @param integer $id
         * @return object
         */
        public function getById ($id) {
            if (!is_integer($id)) { return false; }
            /**
             * @todo Add markdown parsing if needed.
             */
            return parent::selectId($id);
        }

        /**
         * Returns a spresific entry as raw markdown.
         *
         * @param ingeter $id
         * @return object
         */
        public function getAsMd ($id) {
            /**
             * @todo Figure out if markdown parsing can happen in browser.
             */
        }

        /**
         * Adds a new entry to the databse.
         *
         * @param object $data
         * @return boolean
         */
        public function newEntry ($data) {
            if (!is_object($data)) { return false; }
            if (!property_exists($data, 'marker')) { return false; }
            if (!property_exists($data, 'text')) { return false; }
            if (!property_exists($data, 'author')) { return false; }
            return parent::insert($data->marker, $data->text, $data->author);
        }

        /**
         * Updates the entry in the database.
         *
         * @param object $data
         * @return boolean
         */
        public function updateEntry ($data) {
            if (!is_object($data)) { return false; }
            if (!property_exists($data, 'id')) { return false; }
            if (!property_exists($data, 'text')) { return false; }
            return parent::update($data->id, $data->text);
        }

        /**
         * Moves an entry from contents to archive.
         *
         * @param object $data
         * @return boolean
         */
        public function archiveEntry ($data) {
            if (!is_object($data)) { return false; }
            if (!property_exists($data, 'id')) { return false; }
            return parent::moveToArchive($data->id);
        }

    }
?>