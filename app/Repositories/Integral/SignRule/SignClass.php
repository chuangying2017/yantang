<?php
namespace App\Repositories\Integral\SignRule;


use Carbon\Carbon;
use Doctrine\Common\Cache\Cache;
use Illuminate\Support\Facades\Storage;

class SignClass
{
    const SIGN_SEASONS_SPRING = 'spring';//春

    const SIGN_SEASONS_SUMMER = 'summer';//夏

    const SIGN_SEASONS_AUTUMN = 'autumn';//秋

    const SIGN_SEASONS_WINTER = 'winter';//冬

    public static $seasons = ['spring' => [1,2,3],'summer' => [4,5,6],'autumn' => [7,8,9] ,'winter' => [10,11,12]];

    const SIGN_FIRST_REWARD = 'firstReward';//首次签到

    const SIGN_NORMAL_REWARD = 'normal';//日常签到

    const SIGN_INTEGRAL_REPAIR = 'repairSign';//补签

    const SIGN_RETROACTIVE = '1';//开启补签 0 关闭补签

    public static $signMode = ['normal' => '日常签到','firstReward' => '首次签到','repairSign' => '补签扣除积分','continueDays'=>'连续签到获取'];

    const FETCH_SIGN_INTEGRAL = '天';

    const CONTINUE_SIGN_DAYS = 'continueDays';

    const FETCH_BUY_PRODUCT = '购买产品获取积分';

    protected $path;

    protected $file;

    const SIGN_CONVERT_STATUS_ZERO = 0;//没资格
    const SIGN_CONVERT_STATUS_ONE = 1;//待领取
    const SIGN_CONVERT_STATUS_TWO = 2;//已领取
    const SIGN_CONVERT_STATUS_THREE = 3;//未开启
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

    public static function fetchSeasons()
    {
        foreach (self::$seasons as $key => $val)
        {
            if (in_array(Carbon::now()->month,$val))
            {
                return $key;
            }
        }
    }
}