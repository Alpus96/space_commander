<?php

    /**
     * @uses Mysql Alexander Ljungberg Perme
     */
    require_once ROOT_PATH.'/lib/socket/mysql.php';

    /**
     * @todo Review code.
     */

    /**
     * Handles database communication for admin actions.
     */
    class AdminModel extends Mysql {

        static private $query;

        /**
         * Initates the class by constructing the parent Mysql class and defining queries.
         */
        protected function __construct () {
            parent::__construct();
            self::$query = (object)[
                'selectAll' => 'SELECT USERNAME, TYPE, LOCKED FROM USERS',
                'insert' => 'INSERT INTO USERS SET USERNAME = ?, HASH = ?, TYPE = ?',
                'invertLock' => 'UPDATE USERS SET LOCKED = NOT LOCKED WHERE USERNAME = ?',
                'updateType' => 'UPDATE USERS SET TYPE = ? WHERE USERNAME = ?',
                'updatePass' => 'UPDATE USERS SET HASH = ? WHERE USERNAME = ?',
                'delete' => 'DELETE * FROM USERS WHERE USERNAME = ?'
            ];
        }

        /**
         * Gets the username, type and locked status of all users in the database.
         *
         * @return array|boolean
         */
        protected function getUsers () {
            //  Connect to the database.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->selectAll)) {
                $query->execute();
                //  Get the result
                $query->bind_result($username, $type, $locked);
                $data = [];
                while ($query->fetch()) {
                    $user = (object)[
                        'username' => $username,
                        'type' => $type,
                        'locked' => $locked
                    ];
                    array_push($data, $user);
                }
                //  Close the query and the connection before returning the data.
                $query->close();
                $conn->close();
                return $data;
            }
            //  Close the connection and return false if the query could not be run.
            $conn->close();
            return false;
        }

        /**
         * Inserts a new user to the database.
         *
         * @param string $username
         * @param string $hash
         * @param integer $type
         * @return boolean
         */
        protected function createNewUser ($username, $hash, $type) {
            //  Validate types of passed data.
            if (!is_string($username)) { return false; }
            if (!is_string($hash)) { return false; }
            if (!is_integer($type)) {return false; }
            //  Connect to the database.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->insert)) {
                $query->bind_param('ssi', $username, $hash, $type);
                $query->execute();
                //  Confirm success.
                $was_successful = $query->affected_rows > 0 ? true : false;
                //  Close query and connection before returning success status.
                $query->close();
                $conn->close();
                return $was_successful;
            }
            //  If the query could not be run close the database connection and return success as false.
            $conn->close();
            return false;
        }

        /**
         * Inverts the locked status for a user.
         *
         * @param string $username
         * @return boolean
         */
        protected function toggleLock ($username) {
            //  Verify the passed parameter.
            if (!is_string($username)) { return false; }
            //  Connect to the database.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->invertLock)) {
                $query->bind_param('s', $username);
                $query->execute();
                //  Confirm the query was successful.
                $was_successful = $query->affected_rows > 0 ? true : false;
                //  Close the query and connection before returning the success status.
                $query->close();
                $conn->close();
                return $was_successful;
            }
            //  If the query could not be run close the connection and return success false.
            $conn->close();
            return false;
        }

        /**
         * Updates the type for a user.
         *
         * @param string $username
         * @param integer $new_type
         * @return boolean
         */
        protected function setType ($username, $new_type) {
            //  Verify the passed parameters.
            if (!is_string($username)) { return false; }
            if (!is_integer($new_type)) { return false; }
            //  Connect to the database.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->preapre(self::$query->updateType)) {
                $query->bind_param('is', $new_type, $username);
                $query->execute();
                //  Confirm the query was successful.
                $was_successful = $query->affected_rows > 0 ? true : false;
                //  Close the query and the connection before returning the success status.
                $query->close();
                $conn->close();
                return $was_successful;
            }
            //  If the query could be run close the conection and return success false.
            $conn->close();
            return false;
        }

        /**
         * Sets a new password for a user.
         *
         * @param string $username
         * @param string $new_pass
         * @return boolean
         */
        protected function setPassword ($username, $new_pass) {
            //  Verify the passed parameters.
            if (!is_string($username)) { return false; }
            if (!is_string($new_pass)) { return false; }
            //  Connect to the database.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->updatePass)) {
                $query->bind_param('ss', $new_pass, $username);
                $query->execute();
                //  Confirm the query was successful.
                $was_successful = $query->affected_rows > 0 ? true : false;
                //  Close query and connection before returning the success status.
                $query->close();
                $conn->close();
                return $was_successful;
            }
            //  If the query could not be run close the connection and return success false.
            $conn->close();
            return false;
        }

        /**
         * Deletes a user from the database.
         *
         * @param string $username
         * @return boolean
         */
        protected function delete ($username) {
            //  Verify the passed parameters.
            if (!is_string($username)) { return false; }
            //  Connect to the databse.
            $conn = parent::connect();
            if(!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->delete)) {
                $query->bind_param('s', $username);
                $query->execute();
                //  Confirm the query was successful.
                $was_successful = $query->affected_rows > 0 ? true : false;
                //  Close the query and connection before returning the success status.
                $query->close();
                $conn->close();
                return $was_successful;
            }
            //  If the query could not be run close the connection and return success false.
            $conn->close();
            return false;
        }

    }
?>