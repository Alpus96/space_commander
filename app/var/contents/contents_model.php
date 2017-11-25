<?php

    /**
     * @todo indexing of contents. (?)
     */
    
    class ContentsModel extends Mysql {

        static private $query;

        protected function __construct () {
            parent::__construct();
            self::$query = (object)[
                'insert' => '',
                'selectMarker' => '',
                'selectId' => '',
                'insertToArchive' => '',
                'delete' => 'DELETE * FROM CONTENTS WHERE ID = ?'
            ];
        }

        /**
         * Inserts a new entry to the database.
         * 
         * @param string $marker
         * @param string $text
         * @param string $author
         * @return boolean
         */
        protected function insert ($marker, $text, $author) {
            //  Verify the passed parameters.
            if (!is_string($marker)) { return false; }
            if (!is_string($text)) { return false; }
            /**
             * @todo Add relation between user and post. 
             *       (+ define action to perform if user is deleted.)
             *       To display correct authorname.
             */
            if (!is_string($author)) { return false; }
            //  Connect to the database.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->insert)) {
                $query->bind_param();
                $query->execute();
                //  Confirm the query had effect.
                $was_successful = $query->affected_rows > 0 ? true : false;
                //  Close the query and database connection before returning query success status.
                $query->close();
                $conn->close();
                return $was_successful;
            }
            //  If the query could not be run close the connection before returning success as false.
            $conn->close();
            return false;
        }
        
        protected function selectMarker ($marker, $offset, $amount) {
            //  Verify the passed parameters.
            if (!is_string($marker)) { return false; }
            if (!is_integer($offset)) { return false; }
            if (!is_integer($amount)) { return false; }
            //  Connect to the databse.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->selectMarker)) {
                $query->bind_param();
                $query->execute();
                //  Get the result data.
                $query->bind_result();
                $data = [];
                while ($query->fetch()) {
                    $entry = (object)[];
                    array_push($result, $entry);
                }
                //  Close the query and the database connection before returning the data.
                $query->close();
                $conn->close();
                return count($result) > 0 ? $result : null;
            }
            //  If the query could not be run close the connection and return success false.
            $conn->close();
            return false; 
        }

        protected function selectId ($id) {
            //  Verify the passed parameter.
            if (!is_integer($id)) { return false; }
            //  Connect to the database.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->selectId)) {
                $query->bind_param();
                $query->execute();
                //  Get the result data.
                $query->bind_result();
                $query->fetch();
                $result = (object)[];
                //  Close the query and database connection before returning the data.
                $query->close();
                $conn->close();
                return $result->text != '' ? $result : null;
            }
            //  If the query could not be run close the database connection and return success false.
            $conn->close();
            return false;
        }

        /**
         * @todo what to do about entries being changed by a different author? 
         *       (anyone except the one that made the original post)
         */

        protected function update ($id, $text) {
            //  Verify the passed parameters.
            if (!is_integer($id)) { return false; }
            if (!is_string($text)) { return false; }
            //  Connect to the database.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->update)) {
                $query->bind_param();
                $query->execute();
                //  Confirm the query was successful.
                $was_successful = $query->affected_rows > 0 ? true : false;
                //  Close the query and connection before returning the success status.
                $query->close();
                $conn->close();
                return $was_successful;
            }
            //  If the query could not be run close the database connection and return success false.
            $conn->close();
            return false;
        }

        protected function moveToArchive ($id) {
            //
            if (!is_integer($id)) { return false; }
            //  
            $entry = $this->selectId($id);
            if (!$entry) { return false; }
            //
            $conn = parent::connect();
            if (!$conn) { return false; }
            //
            if ($query = $conn->prepare(self::$query->insertToArchive)) {
                $query->bind_param('sss', $entry->text, $entry->author, $entry->unix);
                $query->execute();
                //
                $was_successful = $query->affected_rows > 0 ? true : false;
                //
                $query->close();
                if ($was_successful && $query = $conn->prepare(self::$query->delete)) {
                    $query->bind_param('i', $id);
                    $query->execute();
                    //
                    $was_successful = $query->affected_rows > 0 ? true : false;
                    //
                    $query->close();
                    $conn->close();
                    return $was_successful;
                }
                /**
                 * @todo Should archive be removed if delete from contents fails?
                 */
                //
                $conn->close();
                return false;
            }
            //
            $conn->close();
            return false;
        }
        
    }
    
?>