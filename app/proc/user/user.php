<?php
    /**
     * @uses UserModel  Alexander Ljungberg Perme
     * @uses TokenStore Alexander Ljungberg Perme
     */
    require_once ROOT_PATH.'/app/var/user/user_model.php';
    require_once ROOT_PATH.'/lib/jwt_store/proc/jwt_store.php';

    /**
     * Controller for user actions.
     */
    class User extends UserModel {

        private static $token_store;
        private static $user;
        private static $token;

        /**
         * initiates the class by verifying the token is valid.
         *
         * @param string $token
         */
        public function __construct ($token) {
            parent::__construct();
            //  Verify the user token.
            self::$token_store = new TokenStore();
            $new_token = self::$token_store->verify($token);
            //  Save the decoded token and the new token.
            self::$user = $new_token ? self::$token_store->decode($new_token) : false;
            if ($new_token) {
                setcookie('token', $new_token);
            }
        }

        /**
         * Return whether or not the token was valid.
         *
         * @return boolean
         */
        public function isActive () {
            return self::$user ? true : false;
        }

        /**
         * Gets the authorname for the active user.
         *
         * @return string
         */
        public function showName () {
            return self::$user->author_name;
        }

        /**
         * Destroys the users token session.
         *
         * @return boolean
         */
        public function logout () {
            return self::$token_store->destroy(self::$token);
        }

        /**
         * Sets the authorname of the active user.
         *
         * @param object $data
         * @return boolean
         */
        public function setNewName ($data) {
            //  Veryfy the parameter is usable.
            if (!is_object($data)) { return false; }
            if (!property_exists($data, 'new_name')) { return false; }
            //  Set the new name and update the token.
            self::$user->authorName = $data->new_name;
            $updated = parent::update(self::$user);
            $cleared = $updated ? self::$token_store->destroy(self::$token) : false;
            $new_token = $cleared ? self::$token_store->create(self::$user) : false;
            //  Set the new token cookie.
            if ($new_token) {
                setcookie('token', $new_token);
            }
            //  Return the result.
            return $new_token ? true : false;
        }

        /**
         * Sets a new password for the active user.
         *
         * @param object $data
         * @return boolean
         */
        public function setNewPassword ($data) {
            //  Veryfy the passed parameter is usable.
            if (!is_object($data)) { return false; }
            if (!property_exists($data, 'old_pass') && is_string($data->old_pass)) { return false; }
            if (!property_exists($data, 'new_pass') && is_string($data->new_pass)) { return false; }
            if (!property_exists($data, 'conf_new') && is_string($data->conf_new)) { return false; }
            //  Verify the data.
            if (strlen($data->new_pass) < 6) { return false; }
            if (password_verify($data->old_pass, self::$user->hash) && $data->new_pass === $data->conf_new) {
                //  Update the new password and create a new token.
                self::$user->hash = password_hash($data->new_pass, PASSWORD_DEFAULT);
                $updated = parent::update(self::$user);
                $cleared = $updated ? self::$token_store->destroy(self::$token) : false;
                $new_token = $cleared ? self::$token_store->create(self::$user) : false;
                //  Set the new token cookie.
                if ($new_token) {
                    setcookie('token', $new_token);
                }
                //  Return the result.
                return $new_token ? true : false;
            }
            //  Return false if the confirmation password did not match.
            return false;
        }

        /**
         * Creates a new token session if the username and 
         * password matches a set in the database.
         *
         * @param object $data
         * @return boolean
         */
        public static function login ($data) {
            //  Re-construct parent in case of static call.
            parent::__construct();
            //  Validate the passed parameter is usable.
            if (!is_object($data)) { return false; }
            if (!property_exists($data, 'username')) { return false; }
            if (!property_exists($data, 'password')) { return false; }
            //  Get the user with the etered username.
            $user = parent::getByUsername($data->username);
            //  Verify the given password.
            if (!$user || !password_verify($data->password, $user->hash)) { return false; }
            //  Create a token, set the token cookie and rreturn the result.
            $token = self::$token_store->create($user);
            if ($token) {
                setcookie('token', $token);
                return true;
            }
            return false;
        }

    }
?>