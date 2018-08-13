<?php
namespace App\Repositories\Integral\SignRule;


use Doctrine\Common\Cache\Cache;
use Illuminate\Support\Facades\Storage;

class SignClass
{
    protected $path;

    protected $file;
    /**
     * @param $path string
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

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

        if (Storage::disk('local')->exists($this->path))
        {
            $array = json_decode(Storage::disk('local')->get($this->path . $this->file),true);
        }else
        {
            $array = [];
        }

        return $array;
    }

    public function rewriteData($data)
    {
        $jsonObj = json_encode($data,JSON_UNESCAPED_UNICODE);

        $boolean = Storage::disk('local')->put($this->path . $this->file,$jsonObj);

        if ($boolean)
            $result = 'Successfully';
        else
            $result = 'fail';
        return $result;
    }
}