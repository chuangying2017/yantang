<?php
namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\BackendController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 10/12/2015
 * Time: 5:57 PM
 */
class FrontpageController extends BackendController
{
    public function index()
    {

        return view('backend.setting.basic');
    }
}
