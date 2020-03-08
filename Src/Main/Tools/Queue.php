<?php


namespace Tools;


class Queue {

    /**
     * 队列list
     *
     * @var array
     */
    protected $items = [];

    /**
     * 创建队列元素
     *
     * @param $element
     * @param $priority
     * @return array
     */
    public function queueElement($element, $priority) {
        return [
            'element' => $element,
            'priority' => $priority
        ];
    }

    /**
     * 将数据加入队列
     *
     * @param $element
     * @param null $priority
     * @return array
     */
    public function enqueue($element, $priority = NULL) {

        $queueElement = $this->queueElement($element, $priority);

        if ($this->isEmpty() || $priority === NULL) {
            array_push($this->items, $queueElement);
        } else {
            $add = false;
            foreach ($this->items as $key => $value) {

                if ( $priority < $value['priority'] ) {
                    array_splice($this->items, $key, 0, [$queueElement]);
                    $add = true;
                    break;
                }
            }

            if (! $add) {
                array_push($this->items, $queueElement);
            }
        }

        return $queueElement;
    }

    /**
     * 队列消费
     *
     * @return mixed
     */
    public function dequeue() {

        return array_shift($this->items);
    }

    /**
     * 队列的第一个元素
     *
     * @return mixed
     */
    public function front() {
        return $this->items[0];
    }

    /**
     * 队列是否为空
     *
     * @return bool
     */
    public function isEmpty() {
        return !!! count($this->items);
    }

    /**
     * 队列items大小
     *
     * @return int
     */
    public function size() {
        return count($this->items);
    }
}
