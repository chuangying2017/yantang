<?php
namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 10/12/2015
 * Time: 5:57 PM
 */
class GroupController extends Controller {

    public function index()
    {
    	return view('backend.merchant.index');
        // return view('backend.groups.index');
    }

    public function create()
    {
    	return view('backend.merchant.create');
        // return view('backend.groups.create');
    }
}
