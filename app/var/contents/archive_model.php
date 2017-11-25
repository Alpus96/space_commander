<?php

    class ArchiveModel extends Mysql {

        private static $query;

        protected function __construct () {
            parent::__construct();
            self::$query = (object)[
                'select' => 'SELECT * FROM ARCHIVE',
                'toggleSave' => 'UPDATE ARCHIVE SET TO_SAVE = NOT TO_SAVE WHERE ID = ?'
            ];
        }

        protected function select () {
            //  Connect to the databse.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->select)) {
                /**
                 * @todo Rewrite as non-prepared query.
                 */
                //$query->bind_param();
                $query->execute();
                //  Get the data.
                $query->bind_result($id, $text, $author, $unix);
                $data = [];
                while ($query->fetch()) {
                    $entry = (object)[
                        'id' => $id,
                        'text' => $text,
                        'author' => $author,
                        'timestamp' => strtotime($unix)
                    ];
                    array_push($data, $entry);
                }
                //  Close the query and connection before returning success status.
                $query->close();
                $conn->close();
                return $data;
            }
            //  If the query could not be run lose the connection and return success false.
            $conn->close();
            return false;
        }

        protected function toggleSaveMark ($id) {
            //  Verify passed parameters.
            if (!is_integer($id)) { return false; }
            //  Connect to the databse.
            $conn = parent::connect();
            if (!$conn) { return false; }
            //  Run the query.
            if ($query = $conn->prepare(self::$query->toggleSave)) {
                $query->bind_param('i', $id);
                $query->execute();
                //  Confirm the query was successful.
                $was_successful = $query->affected_rows > 0 ? true : false;
                //  Close the query and connection before returning success status.
                $query->close();
                $conn->close();
                return $was_successful;
            }
            //  If the query could not be run lose the connection and return success false.
            $conn->close();
            return false;
        }
        
    }
?>