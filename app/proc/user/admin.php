<?php

    /**
     * @uses AdminModel Alexander Ljungberg Perme
     * @uses TokenStore Alexander Ljungberg Perme
     * @uses rand_str   Alexadner Ljungberg Perme
     */
    require_once ROOT_PATH.'/app/var/admin_model.php';
    require_once ROOT_PATH.'/lib/jwt_store/proc/jwt_store.php';
    require_once ROOT_PATH.'/lib/rand_str.php';

    /**
     * Controller for the admin functionality.
     */
    class Admin extends AdminModel {

        private static $token_store;
        private static $admin;
        private static $token;

        /**
         * Initiates the class by confirming the token belongs to an abmin.
         * 
         * @param string $token
         */
        public function __construct ($token) {
            parent::__construct();
            //  Verify the user token.
            self::$token_store = new TokenStore();
            $new_token = self::$token_store->verify($token);
            //  Save the decoded token and the new token.
            self::$admin = $new_token ? self::$token_store->decode($new_token) : false;
            self::$admin = self::$admin && self::$admin->type === 2 ? self::$admin : false;
            if (self::$admin && $new_token) {
                setcookie('token', $new_token);
            }
        }

        /**
         * Returns the status of whether or not the token did belong to an admin user.
         *
         * @return boolean
         */
        public function isActive() {
            return self::$admin ? true : false;
        }

        /**
         * Returns a list of all users, except the main admin and the active admin.
         *
         * @return array
         */
        public function showUsers () {
            //  Confirm the requestie is an abmin.
            if (!self::isActive()) { return false; }
            //  Get all users.
            $users = parent::getUsers();
            //  Remove self and the main 'admin' user.
            foreach ($users as $i => $user) {
                if ($user->username == 'admin' || ($user->username == self::$admin->username && $user->type == 2)) {
                    array_splice($users, $i, 1);
                }
            }
            //  Return the users.
            return $users;
        }
        
        /**
         * Registers a user to the database.
         *
         * @param object $data
         * @return boolean
         */
        public function addUser ($data) {
            //  Confirm the request came from an admin.
            if (!self::isActive()) { return false; }
            //  Validate the passed parameters.
            if (!is_object($data)) { return false; }
            if (!property_exists($data, 'username')) { return false; }
            if (!property_exists($data, 'password') && strlen($data->password) > 5) { return false; }
            if (!property_exists($data, 'conf_pass')) { return false; }
            if (!property_exists($data, 'type')) { return false; }
            //  Create the user.
            $created = false;
            if ($data->password === $data->conf_pass) {
                $hash = password_hash($data->password, PASSWORD_DEFAULT);
                $created = parent::createNewUser($data->username, $hash, $data->type);
            }
            //  Return result.
            return $created;
        }

        /**
         * Toggles the locked status of a user allowing or disallowing 
         * them to login and logs them out if active.
         * 
         * @todo Logout the user being locked if active.
         *
         * @param object $data
         * @return boolean
         */
        public function toggleUserLock ($data) {
            //  Confirm the request came from an admin.
            if (!self::isActive()) { return false; }
            //  Validate the passed parameters.
            if (!is_object($data)) { return false; }
            if (!property_exists($data, 'username')) { return false; }
            //  Toggle the lock and return result.
            return parent::toggleLock($data->username);
        }

        /**
         * Sets a user to specified type.
         *
         * @param object $data
         * @return boolean
         */
        public function setUserType ($data) {
            //  Confirm the request came from an admin.
            if (!self::isActive()) { return false; }
            //  Validate the passed parameters.
            if (!is_object($data)) { return false; }
            if (!property_exists($data, 'username')) { return false; }
            if (!property_exists($data, 'type')) { return false; }
            //  Set the new type and return the result.
            return parent::setType($data->username, $data->type);
        }

        /**
         * Sets a users password to a random string and returns it to use for temporary use.
         * 
         * @todo Flag temporary password to prompt pw change.
         *
         * @param object $data
         * @return string|boolean
         */
        public function resetUserPass ($data) {
            //  Confirm the request came from an admin.
            if (!self::isActive()) { return false; }
            //  Validate the passed parameters.
            if (!is_object($data)) { return false; }
            if (!property_exists($data, 'username')) { return false; }
            //  Set the new password.
            $new_pass = rand_str();
            $is_updated = parent::setPassword($data->username, $new_pass);
            //  Return the new password if updated.
            return $is_updated ? $new_pass : false;
        }

        /**
         * Removes a user from database. This is ireversable.
         *
         * @param object $data
         * @return boolean
         */
        public function removeUser ($data) {
            //  Confirm the request came from an admin.
            if (!self::isActive()) { return false; }
            //  Validate the passed parameters.
            if (!is_object($data)) { return false; }
            if (!property_exists($data, 'username')) { return false; }
            //  Delete the user and return result.
            return parent::delete($data->username);
        }

    }
?>