<?php

    /**
     * @global ROOT_PATH ; System root path of the project.
     */
    define('ROOT_PATH', __DIR__);
    
        /**
         * @uses LogSocket Alexander Ljungberg Perme
         * @uses Responese Alexander Ljungberg Perme
         */
        require_once ROOT_PATH.'/app/proc/base/response.php';
        require_once ROOT_PATH.'/lib/srv/log.php';

    /**
     * This file handels seting the root path and instansing 
     * the response handler with the requested url and method.
     * Also shows error page if responder is unable to give resopnse.
     *
     * @category Request handling
     * @package space_commander
     * @version 1.0.0
     * 
     * @author Alexander Ljungberg Perme <alex.perme@gmail.com>
     * @copyright 2017 Alexander Ljungberg Perme
     * @license MIT
     */

    //  Try creating a new response instnace. If the 
    //  responder threw an Exception set it as the response.
    $response = null;
    try {
        $response = new Response($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    } catch (Exception $e) {
        $response = $e;
    }

    //  Confirm there was a valid response, else respond with the 
    //  error page / unsuccessful response and log the Exception.
    if (!$response || $response instanceof Exception) {
        //  Send the response.
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            echo file_get_contents(ROOT_PATH.'err/err_ui.html');
        } else {
            echo json_encode((object)['success' => false]);
        }
        //  Log the Exception.
        $log_soc = new LogSocket(ROOT_PATH.'/lib/srv/logs', 'Exception_Log');
        $log_soc->log(json_encode($response));
    }

 ?>