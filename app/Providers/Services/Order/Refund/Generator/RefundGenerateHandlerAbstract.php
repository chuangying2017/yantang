<?php namespace App\Services\Order\Refund\Generator;
abstract class RefundGenerateHandlerAbstract {

    /*
   * @var GenerateHandlerAbstract $handler
   */
    protected $handler = null;

    public abstract function handle(TempRefundOrder $temp_order);

    /**
     * @param RefundGenerateHandlerAbstract $handler
     */
    public function handleWith(RefundGenerateHandlerAbstract $handler)
    {
        $this->handler = $handler;
    }

    public function next(TempRefundOrder $temp_order)
    {
        if ($this->handler) {
            $this->handler->handle($temp_order);
        }

        return $temp_order;
    }

}
