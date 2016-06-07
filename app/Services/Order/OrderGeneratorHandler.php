<?php namespace App\Services\Order;
trait OrderGeneratorHandler {

    private function getOrderGenerateHandler($config)
    {
        $starter = null;
        $previous_handler = null;

        foreach ($config as $handler_name) {
            $current_handler = app()->make($handler_name);
            if (is_null($starter)) {
                $starter = $current_handler;
            } else {
                $previous_handler->handleWith($current_handler);
            }
            $previous_handler = $current_handler;
        }

        return $starter;
    }

}
