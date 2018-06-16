<?php
/**
 * 请尊重作者的劳动成果，保留本注释
 * Created by WenzhouChan <wenzhouchan@gmail.com>.
 * Author: WenzhouChan <https://NowTime.cc>
 * Date: 2018/6/15
 * Time: 19:49
 * GitHub Link: https://github.com/PrintNow/Make-Funny-Image
 * Issues Link：https://github.com/PrintNow/Make-Funny-Image/issues
 */


namespace app\api\controller;

use think\Controller;
use think\facade\Env;

class Make extends Controller
{
    public function index()
    {

        /**
         * $_POST['type']       模板英文名(英文+数字
         * $_POST['subtitle']   字幕(是一个数组
         * $_POST['version']    版本号(Make Funny Image 版本号，暂时无用处
         */

        if(!isset($_POST['type']) || !isset($_POST['subtitle']) || !isset($_POST['version'])){
            return json([
                'code' => 403,
                'msg' => 'what?'
            ]);
        }



        /**
         * $ROOT_PATH       获取根目录
         * $template_index  将 `public/static/template_index.php` 里的数组赋值给 $template_index
         */
        $ROOT_PATH = Env::get('root_path');
        $template_index = require_once $ROOT_PATH.'template/index.php';



        /**
         * $pick_template   详见 `/application/common.php`
         */
        $pick_template = pick_template($template_index, $_POST['type']);



        /**
         * 判断h是否存在该模板
         */
        if($pick_template === false){
            return json([
                'code' => 404,
                'mag' => 'the `'.$_POST['en_name'].'` template not found'
            ]);
        }

        /**
         * 判断是否开启了 `exec` 函数
         */
        if(!function_exists('exec')){
            return json([
                'code' => 500,
                'msg' => '请网站管理员开启 exec 函数'
            ]);
        }



        /**
         * 判断 `/template/cache` 目录是否存在，不存在则尝试创建
         */
        if(!file_exists($ROOT_PATH.'public/static/cache')){
            if(mkdir($ROOT_PATH.'public/static/cache') === false){
                return json([
                    'code' => 503,
                    'msg' => '由于 public/static/cache 不存在，系统尝试创建该目录，但创建失败，请网站管理员手动创建该目录，并给予 755 权限'
                ]);
            }
        }



        /**
         * $video_path              模板视频绝对路径
         * $subtitle_path           模板字幕绝对路径
         * $subtitle_cache_path     [临时缓存]模板字幕绝对路径
         * $subtitle_cache_path     生成的 GIF 绝对路径
         */
        $time = time();
        $video_path = $ROOT_PATH.'template/'.$pick_template['en_name'].'/template.mp4';
        $subtitle_path = $ROOT_PATH.'template/'.$pick_template['en_name'].'/template.ass';
        $subtitle_cache_path = $ROOT_PATH.'public/static/cache/'.$pick_template['en_name'].'_'.$time.'.ass';
        $gif_cache_path = $ROOT_PATH.'public/static/cache/'.$pick_template['en_name'].'_'.$time.'.gif';

        /**
         * $get_subtitle_path   获取字幕
         * $change_subtitle     替换字幕
         */
        $get_subtitle = file_get_contents($subtitle_path);
        for($i=0;$i<count($_POST['subtitle']);$i++){
            $str_source[$i] = '<?=['.$i.']=?>';
        }
        $change_subtitle = str_replace($str_source, $_POST['subtitle'], $get_subtitle);


        /**
         * 将 $change_subtitle 写入临时缓存字幕文件 $subtitle_cache_path
         */
        if(file_put_contents($subtitle_cache_path, $change_subtitle) === false){
            return json([
                'code' => 501,
                'msg' => '字幕临时缓存文件创建失败，请网站管理员检查 public/static/cache 是否给予了 755 权限(针对 Linux)。Windows 下是 `可读可写`，具体百度'
            ]);
        }

        /**
         * 对 windows 特别处理
         * ffmpegg -y -i 视频路径 -vf 'ass=字幕路径' 输出图片的路径
         */
        if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $video_path = str_replace('\\', '/', substr($video_path,2));
            $subtitle_cache_path = str_replace('\\', '/', substr($subtitle_cache_path,2));
            $gif_cache_path = str_replace('\\', '/', substr($gif_cache_path,2));
        }

        $command = <<<CMD
ffmpeg -y -i $video_path -vf "ass=$subtitle_cache_path" $gif_cache_path 2>&1
CMD;
        exec($command,$output);

        /**
         * 检查 ffmpeg 是否安装
         * 执行命令，检查 $output[0] 值是否包含 command not found
         * 如果包含，则说明 ffmpeg 未安装或未设置好环境变量
         */
        if(strpos($output[0], 'command not found') !== false){
            return json([
                'code' => 501,
                'msg' => '请网站管理员安装 ffmpeg 命令'
            ]);
        }

        /**
         * 自己估算的
         * 具体自己断点测试
         */
        if(count($output) > 6){
            return json([
                'code' => 200,
                'msg' => '生成成功',
                'gif_path' => '/static/cache/'.$pick_template['en_name'].'_'.$time.'.gif'
            ]);
        }else{
            if(Env::get('app_debug') === true){
                $debug = [
                    'command' => $command,
                    'msg' => 'Windows 下生成失败比较头疼，可能是权限问题。Windows Server 系统应该会没问题吧'
                ];
            }else{
                $debug = false;
            }

            return json([
                'code' => 505,
                'msg' => '生成失败，请网站管理员检查 ffmpeg 是否正确安装。Windows XP、7、8、10 搭建的 PHP 环境权限可能有问题，Windows Server 可能就不会出现这个问题',
                'debug' => $debug,
            ]);
        }

        unlink($subtitle_cache_path);//删除临时字幕文件

    }
}