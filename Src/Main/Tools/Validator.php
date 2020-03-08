<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/7/9
 * Time: 14:19
 */

namespace Tools;

class Validator
{
    const IP = 'IP';
    const PASS = '密码';
    const EMAIL = '邮件';
    const IMAGE = '图像';
    const PHONE  = '电话';
    const NUMBER = '数字';
    const URL = '网页地址';
    const EXISTS = '判断是否存在';
    const REQUIRE = '判断是否为空';

    /**
     * 规则列表
     * */
    private static $rule_list = [
        'ip' =>self::IP,
        'url' => self::URL,
        'pass' =>self::PASS,
        'email' =>self::EMAIL,
        'phone' =>self::PHONE,
        'number' =>self::NUMBER,
        'exists' =>self::EXISTS,
        'required' =>self::REQUIRE,
    ];

    /**
     * 规则列表
     * */
    private static $rule_pattern = [
        self::NUMBER => '/^[0-9]*$/',
        self::PASS => '/^[a-z0-9_-]{3,16}$/',
        self::EMAIL => '/^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$/',
        self::IP => '/((2[0-4]\d|25[0-5]|[01]?\d\d?)\.){3}(2[0-4]\d|25[0-5]|[01]?\d\d?)/',
        self::IMAGE => '/.*\.[bmp|jpg|png|tif|gif|pcx|tga|exif|fpx|svg|psd|cdr|pcd|dxf|ufo|eps|ai|raw|WMF|webp]/',
        self::URL => '/^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/|www\.)(([A-Za-z0-9-~]+)\.)+([A-Za-z0-9-~\/])+$/',
        self::PHONE => '/^[1](([3][0-9])|([4][5-9])|([5][0-3,5-9])|([6][5,6])|([7][0-8])|([8][0-9])|([9][1,8,9]))[0-9]{8}$/',
    ];

    /**
     * 验证状态
     * */
    private static $_status;

    /**
     * 错误字段记录
     * */
    private static $error_info;

    /**
     * 实例对象
     * @var self $instance
     * */
    public static $instance;

    /**
     * 复制一份数据
     * */
    private static $data_copy;

    /**
     * 构造一个对象
     * @param array $data   数据
     * @param array $rules   规则
     * @return self
     * */
    public static function make(array $data, array $rules = []){

        if(!(self::$instance instanceof self))
            self::$instance = new static();

        self::$_status = true;
        $rule_run_locked = false;

        empty($rules) ? $rules = $data : $rule_run_locked = true;
        foreach ($rules as $key => $rule_alias){

            if($rule_run_locked){
                foreach (explode("|" , $rule_alias) as $rule){

                    if((($rule == 'exists' || $rule == 'required') && !isset($data[$key])) || ($rule == 'required' && isset($data[$key]) && $data[$key] !== 0 && empty($data[$key]))){
                        self::$_status = false;
                        self::$error_info[] = $rule;
                        dump_vars('The Key Validator Error：' . $key);
                        break 2;
                    }

                    if(!isset(self::$rule_list[$rule]) || !isset($data[$key]) || $rule == 'exists' || $rule == 'required')
                        continue;

                    $res = preg_match(self::$rule_pattern[self::$rule_list[$rule]], $data[$key]);

                    if(empty($res)){
                        self::$_status = false;
                        self::$error_info[] = $rule;
                        dump_vars('The Key Validator Error：' . $key);
                        break 2;
                    }
                }
            }

            if(!empty($data[$key]) && !is_numeric($data[$key]) && empty($data[$key])){
                self::$error_info[] = $key;
                self::$_status = false;
                dump_vars('The Key Validator Error：' . $key);
                break;
            }
        }
        self::$data_copy = $data;
        return self::$instance;
    }

    /**
     * 验证
     * @param string $param
     * @param \Closure $func
     * @return Validator
     * */
    public function validate(string $param, \Closure $func){
        if(!isset(self::$data_copy[$param]))
            throw new \RuntimeException("Something Wrong In Validator...");
        !($func instanceof \Closure) ?: self::$_status = call_user_func($func, self::$data_copy[$param]);
        return self::$instance;
    }

    /**
     * @return mixed
     */
    public function fail()
    {
        self::destruction();
        return empty(self::$_status) && !self::$_status ? true : false;
    }

    /**
     * @return mixed
     */
    public function getErrorInfo()
    {
        return self::$error_info;
    }

    /**
     * destruct
     * */
    public function destruction(){
        self::$instance = null;
        self::$error_info = [];
    }
}
