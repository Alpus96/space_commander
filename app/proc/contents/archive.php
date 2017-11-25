<?php

    /**
     * @uses ArchiveModel ---
     */
    require_once ROOT_PATH.'/app/var/contents/archive_model.php';

    /**
     * Handles the archived contents.
     */
    class Archive extends ArchiveModel {

        /**
         * Initiates the class.
         */
        public function __construct () {

        }

        /**
         * Gets all archived contents from the database.
         *
         * @return array
         */
        public function getAll () {
            
        }

        /**
         * Restores an archived entry to contents.
         *
         * @param object $data
         * @return boolean
         */
        public function restore ($data) {

        }

        /**
         * Marks an archived antry to be saved passed the typical limit.
         *
         * @param object $data
         * @return boolean
         */
        public function markToSave ($data) {

        }

        /**
         * Deletes an archived entry, without restoring it as contents. 
         * Therefore this action is ireversable.
         *
         * @param object $data
         * @return boolean
         */
        public function remove ($data) {

        }

    }
?>