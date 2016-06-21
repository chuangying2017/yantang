<?php namespace App\Services\Order\Generator;

abstract class GenerateHandlerAbstract {

    /*
     * @var GenerateHandlerAbstract $handler
     */
    protected $handler = null;

    public abstract function handle(TempOrder $temp_order);

    /**
     * @param GenerateHandlerAbstract $handler
     */
    public function handleWith(GenerateHandlerAbstract $handler)
    {
        $this->handler = $handler;
    }


    public function next(TempOrder $temp_order)
    {
        if ($this->handler) {
            $this->handler->handle($temp_order);
        }

        return $temp_order;
    }

}
