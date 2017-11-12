<?php 

    /**
     * @todo Review code and add comments.
     */

    /**
     * This is the main controller, it synthasises a response on every request.
     */
    class Response {

        private static $token;

        /**
         * Initates the class by confirm the request is valid and getting 
         * token is set and desiding correct response type.
         *
         * @param string $url
         * @param string $method
         */
        public function __construct ($url, $method) {
            parent::__construct();
            //  Verify the passed parameters.
            if (!is_string($url) || !is_string($method)) { return false; }
            if ($method != 'GET' && $method != 'POST') { return false; }

            //  Get the token cookie.
            self::$token = $_COOKIE['token'] ? json_decode($_COOKIE['token'])->value : false;

            //  Determine what request type to respond to.
            if ($method == 'GET') { self::GET($url); }
            else if ($method == 'POST') { self::POST($url); }
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
         * Checks what to GET.
         *
         * @param string $url
         * @return void
         */
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

        /**
         * Checks what the post was and performes an action to respond.
         *
         * @param string $url
         * @return void
         */
        private function POST ($url) {
            //  Get the post data.
            $data = json_decode(file_get_contents('php://input'));

            if ($url == '/login') {
                $logged_in = USER::login($data);
                $response = (object)['success' => $logged_in];
                echo json_encode($response);
            } else if ($url == '/logout') {
                $user = self::$token ? new User($token) : false;
                $result = $user && $user->isActive() ? $user->logout() : true;
                $response = (object)['success' => $result];
                echo json_encode($response);
            } else if ($url == '/updateAuthorName') {
                $user = self::$token ? new User(self::$token) : false;
                $result = $user && $user->isActive() ? $user->setNewName($data) : false;
                $response = (object)['success' => $result];
                echo json_encode($response);
            } else if ($url == '/updatePassword') {
                $user = self::$token ? new User(self::$token) : false;
                $result = $user && $user->isActive() ? $user->setNewPassword($data) : false;
                $response = (object)['success' => $result];
                echo json_encode($response);
            } else if ($url == '/newEntry') {
                $user = self::$token ? new User($token) : false;
                $contents = new Contents();
                $result = $user && $user->isActive() ? $contents->newEntry($data) : false;
                $response = (object)['success' => $result];
                echo json_encode($response);
            } else if ($url == '/updateEntry') {
                $user = self::$token ? new User(self::$token) : false;
                $contents = new Contents();
                $reslut = $user && $user->isActive() ? $contents->updateEntry($data) : false;
                $response = (object)['success' => $result];
                echo json_encode($response);
            } else if ($url == '/archiveEntry') {
                $user = self::$token ? new User(self::$token) : false;
                $contents = new Contents();
                $reslut = $user && $user->isActive() ? $contents->archiveEntry($data) : false;
                $response = (object)['success' => $result];
                echo json_encode($response);
            } else if ($url == '/restoreEntry') {
                $user = self::$token ? new User(self::$token) : false;
                $achive = new Archive();
                $reslut = $user && $user->isActive() ? $achive->restore($data) : false;
                $response = (object)['success' => $result];
                echo json_encode($response);
            } else if ($url == '/markSaveArchived') {
                $user = self::$token ? new User(self::$token) : false;
                $achive = new Archive();
                $reslut = $user && $user->isActive() ? $achive->markToSave($data) : false;
                $response = (object)['success' => $result];
                echo json_encode($response);
            } else if ($url == '/removeArchived') {
                $user = self::$token ? new User(self::$token) : false;
                $achive = new Archive();
                $reslut = $user && $user->isActive() ? $achive->remove($data) : false;
                $response = (object)['success' => $result];
                echo json_encode($response);
            } else if ($url == '/newImage') {
                $user = self::$token ? new User(self::$token) : false;

                /**
                 * @todo Figure out how this works.
                 */

            } else if ($url == '/removeImage') {
                $user = self::$token ? new User(self::$token) : false;
                $image = new Image();
                $reslut = $user && $user->isActive() ? $image->delete($data) : false;
                $response = (object)['success' => $result];
                echo json_encode($response);
            } else if ($url == '/newUser') {
                $admin = self::$token ? new Admin($token) : false;
                $result = $admin && $admin->isActive() ? $admin->addUser($data) : false;
                $response = (object)['success' => $result];
                echo json_encode($response);
            } else if ($url == '/toggleUserLock') {
                $admin = self::$token ? new Admin($token) : false;
                $result = $admin && $admin->isActive() ? $admin->toggleUserLock($data) : false;
                $response = (object)['success' => $result];
                echo json_encode($response);
            } else if ($url == '/updateUserType') {
                $admin = self::$token ? new Admin($token) : false;
                $result = $admin && $admin->isActive() ? $admin->setUserType($data) : false;
                $response = (object)['success' => $result];
                echo json_encode($response);
            } else if ($url == '/resetUserPassword') {
                $admin = self::$token ? new Admin($token) : false;
                $newPass = $admin && $admin->isActive() ? $admin->resetUserPass($data) : false;
                $response = (object)['success' => $newPass ? true : false, 'newPass' => $newPass];
                echo json_encode($response);
            } else if ($url == '/removeUser') {
                $admin = self::$token ? new Admin($token) : false;
                $result = $admin && $admin->isActive() ? $admin->removeUser($data) : false;
                $response = (object)['success' => $result];
                echo json_encode($result);
            } else {
                $response = (object)['success' => false];
                echo json_encode($response);
            }
        }

    }
?>