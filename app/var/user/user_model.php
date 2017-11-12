<?php

    /**
     * @uses Mysql Alexander Ljungberg Perme
     */
    require_once ROOT_PATH.'/lib/socket/mysql.php';

    /**
     * The database model for the user controller.
     */
    class UserModel extends Mysql {

        private static $query;

        /**
         * Initiates the class by constructing the parent Mysql class and defining the queries to be used.
         */
        protected function __construct () {
            parent::__construct();
            self::$query = (object)[
                'select' => 'SELECT HASH, AUTHOR_NAME, TYPE, LOCKED FROM USERS WHERE USERNAME = ?',
                'update' => 'UPDATE USERS SET HASH = ?, AUTHOR_NAME = ? WHERE USERANME = ?'
            ];
        }

        /**
         * Gets user information for specified username.
         *
         * @param string $username
         * @return object|boolean
         */
        protected function getByUsername ($username) {
            //  Verify the passed parameter.
            if (!is_string($username)) { return false; }
            //  Connect to the database.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //Run the query.
            if ($query = $conn->prepare(self::$query->select)) {
                $query->bind_param('s', $username);
                $query->execute();
                //  Get the query result.
                $query->bind_result($hash, $author_name, $type, $locked);
                $query->fetch();
                $data = $locked ? (object)[
                    'hash' => $hash,
                    'author_name' => $author_name,
                    'type' => $type,
                ] : false;
                //  Close the query and database connection before returning the result.
                $query->close();
                $conn->close();
                return $data;
            }
            //  Close the connection and return false if the query could not be run.
            $conn->close();
            return false;
        }

        /**
         * Updates the informaion about a user.
         *
         * @param object $user
         * @return boolean
         */
        protected function update ($user) {
            //  Verify the passed parameters.
            if (!is_object()) { return false; }
            if (!property_exists($user, 'hash')) { return false; }
            if (!property_exists($user, 'author_name')) { return false; }
            if (!property_exists($user, 'username')) { return false; }
            //  Connect to the database.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->update)) {
                $query->bind_param('sss', $user->hash, $user->author_name, $user->username);
                $query->execute();
                //  Confirm the success.
                $was_successful = $query->affected_rows > 0 ? true : false;
                //  Close query and connection before returning the success confirmation.
                $query->close();
                $conn->close();
                return $was_successful;
            }
            //  Close the connection and return false if the query could not be run.
            $conn->close();
            return false;
        }

    }
?>