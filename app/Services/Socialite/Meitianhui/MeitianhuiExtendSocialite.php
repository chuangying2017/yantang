<?php namespace App\Services\Socialite\Meitianhui;

use SocialiteProviders\Manager\SocialiteWasCalled;

class MeitianhuiExtendSocialite {

    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite(
            'meitianhui', __NAMESPACE__ . '\Provider'
        );
    }

}
