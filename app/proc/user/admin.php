<?php

    /**
     * Controller for the admin functionality.
     */
    class Admin extends AdminModel {

        /**
         * Initiates the class by confirming the token belongs to an abmin.
         * 
         * @param string $token
         */
        public function __construct ($token) {
            parent::__construct();
        }

        /**
         * Returns the status of whether or not the token did belong to an admin user.
         *
         * @return boolean
         */
        public function isActive() {

        }

        /**
         * Returns a list of all users, except the main admin and the active admin.
         *
         * @return object
         */
        public function showUsers () {

        }
        
        /**
         * Registers a user to the database.
         *
         * @param object $data
         * @return boolean
         */
        public function addUser ($data) {

        }

        /**
         * Toggles the locked status of a user allowing or disallowing 
         * them to login and logs them out if active.
         *
         * @param object $data
         * @return boolean
         */
        public function toggleUserLock ($data) {

        }

        /**
         * Sets a user to specified type.
         *
         * @param object $data
         * @return boolean
         */
        public function setUserType ($data) {

        }

        /**
         * Sets a users password to a random string and returns it to use for temporary use.
         * 
         * @todo flag temporary password.
         *
         * @param object $data
         * @return boolean
         */
        public function resetUserPass ($data) {

        }

        /**
         * Removes a user from database. This is ireversable.
         *
         * @param object $data
         * @return boolean
         */
        public function removeUser ($data) {
            
        }

    }
?>