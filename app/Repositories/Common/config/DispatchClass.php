<?php
namespace App\Repositories\Common\config;

class DispatchClass
{
    public static function get_container(array $config)
    {
        $starter = null;
        $previous_handler = null;

        foreach ($config as $handler_name) {
            $current_handler = \App::make($handler_name);
            if (is_null($starter)) {
                $starter = $current_handler;
            } else {
                $previous_handler->editWith($current_handler);
            }
            $previous_handler = $current_handler;
        }

        return $starter;
    }
}