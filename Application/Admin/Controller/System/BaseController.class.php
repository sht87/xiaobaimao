<?php

//decode by http://www.yunlu99.com/
namespace Admin\Controller\System;

use Think\Controller;
class BaseController extends Controller
{
    public function _initialize()
    {
//        $domainWhileList = array('www.yunyjia.com', 'app.yunyjia.com', 'yunyjia.com', 'api.yunyjia.com', 'localhost', '127.0.0.1', '112.74.32.120');
//        if (!$this->isWhileDomain($domainWhileList)) {
//            exit('系统内部错误，请联系管理员!');
//        }
        if (!check_mysql()) {
            header("Location:http://" . $_SERVER['HTTP_HOST'] . "/remind/1006.html");
            exit;
        }
        if (empty($_SESSION['AdminInfo']['Admin'])) {
            $this->redirect('System/Login/login');
        } else {
            $last_time = strtotime($_SESSION['AdminInfo']['LastLoginTime']);
            $current_time = strtotime(date("Y-m-d H:i:s"));
            $active_time = get_basic_info('Session');
            if (($current_time - $last_time) / 60 > $active_time) {
                session('AdminInfo', null);
                $this->redirect('System/Login/login');
            } else {
                $_SESSION['AdminInfo']['LastLoginTime'] = date('Y-m-d H:i:s', time());
            }
        }
        if (!is_permission()) {
            echo "<br/>403错误:<br/>很抱歉，您没有此操作的操作权限！";
            exit;
        }
        record_operation_log();
    }
    protected function ajaxReturn($status = 0, $msg = '成功', $data = '', $dialog = '')
    {
        $return_arr = array();
        if (is_array($status)) {
            $return_arr = $status;
        } else {
            $return_arr = array('result' => $status, 'message' => $msg, 'des' => $data, 'dialog' => $dialog);
        }
        ob_clean();
        echo json_encode($return_arr);
        exit;
    }
    private function isWhileDomain($domainWhileList)
    {
        $domain = $_SERVER['SERVER_NAME'];
        return in_array($domain, $domainWhileList);
    }
}