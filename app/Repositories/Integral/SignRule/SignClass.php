<?php
namespace App\Repositories\Integral\SignRule;


use Doctrine\Common\Cache\Cache;
use Illuminate\Support\Facades\Storage;

class SignClass
{
    const SIGN_SEASONS_SPRING = 'spring';//春

    const SIGN_SEASONS_SUMMER = 'summer';//夏

    const SIGN_SEASONS_AUTUMN = 'autumn';//秋

    const SIGN_SEASONS_WINTER = 'winter';//冬

    public static $seasons = ['spring' => [1,2,3],'summer' => [4,5,6],'autumn' => [7,8,9] ,'winter' => [10,11,12]];

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