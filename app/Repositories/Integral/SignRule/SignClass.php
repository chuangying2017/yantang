<?php
namespace App\Repositories\Integral\SignRule;


use Doctrine\Common\Cache\Cache;

class SignClass
{
    protected $file;

    /**
     * @param $file string
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return array
     */
    public function get()
    {

        if (file_exists($this->file))
        {
            $array = json_decode($this->file,true);
        }else
        {
            $array = [];
        }

        return $array;
    }

    public function rewriteData($data)
    {
        $jsonObj = json_encode($data,JSON_UNESCAPED_UNICODE);

        $int = file_put_contents($this->file,$jsonObj);

        if (is_numeric($int))
            $result = 'Successfully';
        else
            $result = 'fail';
        return $result;
    }
}