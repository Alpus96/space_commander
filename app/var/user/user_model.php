<?php

    class UserModel extends Mysql {

        private static $query;

        protected function __construct () {
            parent::__construct();
            self::$query = (object)[
                'select' => 'SELECT HASH, AUTHOR_NAME, TYPE, LOCKED FROM USERS WHERE USERNAME = ?',
                'update' => 'UPDATE USERS SET HASH = ?, AUTHOR_NAME = ? WHERE USERANME = ?'
            ];
        }

        protected function getByUsername ($username) {
            if (!is_string($username)) { return false; }
            $conn = parent::connect();
            if (!$conn) { return false; }
            if ($query = $conn->prepare(self::$query->select)) {
                $query->bind_param('s', $username);
                $query->execute();
                $query->bind_result($hash, $author_name, $type, $locked);
                $query->fetch();
                $data = $locked ? (object)[
                    'hash' => $hash,
                    'author_name' => $author_name,
                    'type' => $type,
                ] : false;
                $query->close();
                $conn->close();
                return $data;
            }
            $conn->close();
            return false;
        }

        protected function update ($user) {
            if (!is_object()) { return false; }
            if (!property_exists($user, 'hash')) { return false; }
            if (!property_exists($user, 'author_name')) { return false; }
            if (!property_exists($user, 'username')) { return false; }
            $conn = parent::connect();
            if (!$conn) { return false; }
            if ($query = $conn->prepare(self::$query->update)) {
                $query->bind_param('sss', $user->hash, $user->author_name, $user->username);
                $query->execute();
                $was_successful = $query->affected_rows > 0 ? true : false;
                $query->close();
                $conn->close();
                return $was_successful;
            }
            $conn->close();
            return false;
        }

    }
?>