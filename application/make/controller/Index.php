<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/15
 * Time: 18:42
 */

namespace app\make\controller;

use think\Controller;
use think\facade\Env;

class Index extends Controller
{
    public function index()
    {
        return 'https://NowTime.cc';
    }

    public function make($name)
    {
        $ROOT_PATH = Env::get('root_path');
        $template_index = require_once $ROOT_PATH.'template/index.php';

        $pick_template = pick_template($template_index, $name);

        if($pick_template === false){
            header('HTTP/1.1 302 Fount');
            header('Location: /');
            return 'not found template';
        }

        $this->assign([
            'title' => '制作“'.$pick_template['cn_name'].'”GIF/动图 - 制作有趣的图像',
        ]);
        $this->assign('list', $pick_template);
        return $this->fetch('/index_make');
    }
}