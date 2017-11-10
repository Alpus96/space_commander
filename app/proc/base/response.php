<?php 

    class Response {

        private static $json_soc;
        private static $view_path;
        private static $token;

        public function __construct ($url, $method) {
            parent::__construct();
            if (!is_string($url) || !is_string($method)) { return false; }
            if ($method != 'GET' && $method != 'POST') { return false; }

            self::$json = new JsonSocket(ROOT_PATH.'/lib/etc');
            self::$view_path = self::$json->read('view_paths');
            self::$token = $_COOKIE['token'] ? json_decode($_COOKIE['token'])->value : false;

            if ($method == 'GET') { self::GET($url); }
            else if ($method == 'POST') { self::POST($url); }
        }

        private function GET ($url) {
            if ($url == '/') {
                echo file_get_contents(ROOT_PATH.'/app/ui/index.html');
            } else if ($url == '/contentsByMarker') {
                $contents = new Contents();
                $data = $contents->getByMarker($_GET['marker']);
                $response = (object)['success' => $data ? true : false, 'data' => $data];
                echo json_encode($response);
            } else if ($url == '/contentById') {
                $contents = new Contents();
                $data = $contents->getById($_GET['id']);
                $response = (object)['success' => $data ? true : false, 'data' => $data];
                echo json_encode($response);
            } else if ($url == '/login') {
                echo file_get_contents(ROOT_PATH.'/app/ui/usr/html/login.html');
            } else if ($url == '/cms') {
                $user = self::$token ? new User(self::$token) : false;
                $index = $user && $user->isActive() ? self::makeEditable(file_get_contents(ROOT_PATH.'/app/ui/index.html')) : false;
                if (!$index) {
                    unset($_COOKIE['token']);
                    header('Location: /login');
                    return;
                }
                echo $index;
            } else if ($url == '/contentAsMarkdown') {
                $contents = new Contents();
                $data = $contents->getAsMd($_GET['id']);
                $response = (object)['success' => $data ? true : false, 'data' => $data];
                echo json_encode($response);
            } else if ($url == '/archivedEntries') {
                $achive = new Archive();
                $data = $achive->getAll();
                $response = (object)['success' => $data ? true : false, 'data' => $data];
                echo json_encode($response);
            } else if ($url == '/myAuthorName') {
                $user = self::$token ? new User($token) : false;
                $name = $user && $user->isActive() ? $user->showName() : false;
                $response = (object)['success' => $name ? true : false, 'name' => $name];
                echo json_encode($response);
            } else if ($url == '/allUsers') {
                $admin = self::$token ? new Admin($token) : false;
                $users = $admin && $admin->isActive() ? $admin->showUsers() : false;
                $response = (object)['success' => $users ? true : false, 'users' => $users];
                echo json_encode($users);
            } else {
                echo file_get_contents(ROOT_PATH.'/app/ui/html/404.html');
            }
        }

        private function POST ($url) {
            //  Get the post data.
            $data = json_decode(file_get_contents('php://input'));

            if ($url == '/login') {
                $token = USER::login($data);
            } else if ($url == '/logout') {

            } else if ($url == '/updateAuthorName') {
                
            } else if ($url == '/updatePassword') {
                                
            } else if ($url == '/newEntry') {
                
            } else if ($url == '/updateEntry') {
                
            } else if ($url == '/archiveEntry') {
                
            } else if ($url == '/restoreEntry') {
                
            } else if ($url == '/markSaveArchived') {
                
            } else if ($url == '/removeArchived') {
                
            } else if ($url == '/newImage') {
                
            } else if ($url == '/removeImage') {
                
            } else if ($url == '/newUser') {
                
            } else if ($url == '/toggleUserLock') {
                
            } else if ($url == '/updateUserType') {
                
            } else if ($url == '/resetUserPassword') {
                
            } else if ($url == '/removeUser') {
                
            }
        }

        /**
         * @method GET
         */
        /**
         * @group Resources
         * 
         * /
         * /contentsByMarker
         * /contentById
         * 
         */

        /**
         * @group cms
         * 
         * /cms
         * /contentAsMarkdown
         * /archivedContent
         * 
         */

        /** 
         * @group users
         * 
         * /login
         * /authorName
         * /allUsers
         * 
         */


        /**
         * @method POST
         */
        /** 
         * @group users
         * 
         * /login
         * /logout
         * /updateAuthorName
         * /updatePassword
         * 
         */

        /**
         * @group contents
         * 
         * /newEntry
         * /updateEntry
         * /archiveEntry
         * /restoreEntry
         * /markSaveArchived
         * /removeArchived
         * /newImage
         * /removeImage
         * 
         */

        /** 
         * @group admins
         * 
         * /newUser
         * /toggleUserLock
         * /updateUserType
         * /resetUserPassword
         * /removeUser
         * 
         */
    }
?>