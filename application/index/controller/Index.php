<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Env;

class Index extends Controller
{
    public function index()
    {
        $ROOT_PATH = Env::get('root_path');
        $template_index = require_once $ROOT_PATH.'template/index.php';

        $this->assign('list', $template_index);
        return $this->fetch('/index');
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
