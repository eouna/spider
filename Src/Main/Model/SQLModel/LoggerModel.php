<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/9/10
 * Time: 11:23
 */

namespace Model\SQLModel;

use Illuminate\Support\Facades\DB;

class LoggerModel
{

    /**
     * 数据表名日志
     * */
    private $table = 'action_log';

    const CARD_LOG = "卡牌日志";
    const SHIELD_LOG = "屏蔽日志";

    const OP_ADD = 1;
    const OP_DELETE = 2;
    const OP_EDIT = 3;

    /**
     * 代码
     * */
    private $code = [
        self::CARD_LOG => 100,
        self::SHIELD_LOG => 200,
    ];

    /**
     * 操作别名
     * */
    private $operate = [
        self::OP_ADD => '添加',
        self::OP_EDIT => '更改',
        self::OP_DELETE => '删除',
    ];

    /**
     * 番外卡牌日志
     * @param mixed $uid_info
     * @param string $action_name
     * @param int $operate
     * @param string $action_value
     * @throws
     * @return string
     * */
    public function writeLogger($uid_info, string $action_name, int $operate, string $action_value){
        $u_name = $uid_info->bio;
        $action_string = $this->actionStringFactory($action_name, $operate, $action_value);
        return DB::table($this->table)->insert([
            'uid' => $uid_info->id, 'user_name' => $u_name, 'action_name' => $action_string, 'action_value' => $action_value,
            'action_code' => $this->code[$action_name] . $operate, 'createtime' => time(),
        ]);
    }

    /**
     * @param string $action_name
     * @param int $operate
     * @param string $action_value
     * @return string
     * */
    private function actionStringFactory(string $action_name, int $operate, string $action_value){
        switch ($action_name){
            case self::CARD_LOG:
                return $this->operate[$operate] . "ID为：" . $action_value . " 的卡牌";
            case self::SHIELD_LOG:
                return "从屏蔽列表中" . $this->operate[$operate] . "ID为：" . $action_value . " 的用户";
            default:
                throw new \RuntimeException("not found action_name");
        }
    }
}
