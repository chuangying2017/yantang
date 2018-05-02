<?php namespace App\Services\Socialite;

use Symfony\Component\HttpFoundation\RedirectResponse;


trait ApiStateless {

    public function redirect()
    {
        $state = null;
        return new RedirectResponse($this->getAuthUrl($state));
    }


    protected function hasInvalidState()
    {
        #TODO 提高安全性
        return false;
    }
}
