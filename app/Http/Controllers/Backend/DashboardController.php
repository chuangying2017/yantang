<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Backend
 */
class DashboardController extends BackendController {

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {

        return view('backend.dashboard');
    }
}
