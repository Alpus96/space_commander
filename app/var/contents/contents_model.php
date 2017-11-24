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
            //
            if (!is_string($marker)) { return false; }
            if (!is_string($text)) { return false; }
            /**
             * @todo Add relation between user and post. (+ define action to perform if user is deleted.)
             */
            if (!is_string($author)) { return false; }
            //
            $conn = parent::connect();
            if (!$conn) { return false; }
            //
            if ($query = $conn->prepare(self::$query->insert)) {
                $query->bind_param();
                $query->execute();
                //
                $was_successful = $query->affected_rows > 0 ? true : false;
                //
                $query->close();
                $conn->close();
                return $was_successful;
            }
            //
            $conn->close();
            return false;
        }

        protected function selectMarker ($marker, $offset, $amount) {
            //
            if (!is_string($marker)) { return false; }
            if (!is_integer($offset)) { return false; }
            if (!is_integer($amount)) { return false; }
            //
            $conn = parent::connect();
            if (!$conn) { return false; }
            //
            if ($query = $conn->prepare(self::$query->selectMarker)) {
                $query->bind_param();
                $query->execute();
                //
                $query->bind_result();
                $data = [];
                while ($query->fetch()) {
                    $entry = (object)[];
                    array_push($result, $entry);
                }
                //
                $query->close();
                $conn->close();
                return count($result) > 0 ? $result : null;
            }
            //
            $conn->close();
            return false; 
        }

        protected function selectId ($id) {
            //
            if (!is_integer($id)) { return false; }
            //
            $conn = parent::connect();
            if (!$conn) { return false; }
            //
            if ($query = $conn->prepare(self::$query->selectId)) {
                $query->bind_param();
                $query->execute();
                //
                $query->bind_result();
                $query->fetch();
                $result = (object)[];
                //
                $query->close();
                $conn->close();
                return $result->text != '' ? $result : null;
            }
            //
            $conn->close();
            return false;
        }

        /**
         * @todo what to do about entries being changed by a different author than 'poster'.
         */

        protected function update ($id, $text) {
            //
            if (!is_integer($id)) { return false; }
            if (!is_string($text)) { return false; }
            //
            $conn = parent::connect();
            if (!$conn) { return false; }
            //
            if ($query = $conn->prepare(self::$query->update)) {
                $query->bind_param();
                $query->execute();
                //
                $was_successful = $query->affected_rows > 0 ? true : false;
                //
                $query->close();
                $conn->close();
                return $was_successful;
            }
            //
            $conn->close();
            return false;
        }

        protected function moveToArchive ($id) {

        }
        
    }
    
?>