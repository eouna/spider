<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/3/2
 * Time: 18:05
 */

namespace Src\Provider;

use Tools\CPlus;

class PageManageProvider
{
    /**
     * redis分页类表名
     * @param string $table
     * */
    private $table;
    protected $cur_page;
    protected $offset;
    protected $next_page;
    protected $pre_page;
    protected $step;
    protected $total_page;
    protected $with_score;
    protected $instance;
    protected $check_name = true;
    protected $with_scores = "WITHSCORES";
    protected $order_model = true;
    protected $limit = 100;

    /**
     * 检查是否是分页数据
     * @param string $table
     * @return PageManageModel\static | bool
     * */
    public function table($table){

        if(CPlus::redisKeyType($table) != 4)
            return $this;

        $this->table = $table;
        $this->total_page = ceil($this->count() / $this->limit);
        return $this;
    }

    /**
     * 开始分页的地址
     * @param string $offset
     * @return PageManageModel\static
     * */
    public function offset($offset){
        $this->offset = $offset;
        return $this;
    }

    /**
     * 一页几条数据
     * @param string $limit
     * @return PageManageModel\static
     * */
    public function limit($limit){
        $this->limit = $this->step = $limit;
        return $this;
    }

    /**
     * 是否带上值
     * @param bool $flag
     * @return PageManageModel\static
     * */
    public function withScores($flag = true){
        $this->with_score = $flag;
        return $this;
    }

    /**
     * 获取数据
     * @param bool $is_rev
     * @return array | bool
     * */
    public function get($is_rev = true){

        $this->cur_page = isset($this->cur_page) ? $this->cur_page : 0;
        $this->offset = (isset($this->offset) ? $this->offset : 0);
        $this->limit = $this->offset + $this->step;
        $this->order_model = $is_rev;

        return $this->order_model ?
            CPlus::rediszRevRange($this->table, $this->offset, $this->limit, $this->with_scores) :
            CPlus::rediszRange($this->table, $this->offset, $this->limit, $this->with_scores);
    }

    /**
     * 获取所有页数
     * */
    public function count(){
        return CPlus::redisZcard($this->table);
    }

    /**
     * 获取所有页数
     * */
    public function getTotalPage(){
        return $this->total_page;
    }

    /**
     * 获取数据
     * @param bool $is_rev
     * @return array | bool
     * */
    public function nextPage(bool $is_rev = true){

        $this->offset = $this->cur_page * $this->limit;
        if($this->cur_page >= $this->getTotalPage())
            return [];

        $this->cur_page = isset($this->cur_page) ? $this->cur_page + 1 : 1;
        $this->offset = ($this->cur_page * $this->step) + 1;

        return $this->get(isset($this->order_model) ? $this->order_model : $is_rev);
    }
}
