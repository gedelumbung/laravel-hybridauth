<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App;
use Controller;
use Session;
use View;

use Hybrid_Auth, Hybrid_Endpoint;

class HomeController extends BaseController {

    private $app;

    public function __contruct()
    {
        $this->app = App::getFacadeApplication();
    }

	public function getIndex()
	{
		return View::make('index');
    }

    public function getSocial($action = '', $social = '')
    {
        if (empty($action)) return View::make('index');
        
        if ($action === "auth") {
            try {
                Hybrid_Endpoint::process();
            } catch ( Exception $e ) {
                echo "Error at Hybrid_Endpoint process : $e";
            }

            return;
        }
        $this->auth($social);
    }

    public function getFacebook()
    {
        return $this->auth('facebook');
    }

    public function getLinkedin()
    {
        return $this->auth('linkedin');
    }

    private function auth($social)
    {
        $provider_available = array(
            'facebook'  => 'Facebook',
            'linkedin'  => 'Linkedin',
        );

        $provider = App::make('Hybrid_Auth');
        try {
            $hybridAuthProvider = $provider->authenticate($provider_available[$social]);
            $hybridAuthUserProfile = $hybridAuthProvider->getUserProfile();

            Session::put('firstName', $hybridAuthUserProfile->firstName);
            Session::put('email', $hybridAuthUserProfile->email);
            Session::put('profileURL', $hybridAuthUserProfile->profileURL);
            Session::put('photoURL', $hybridAuthUserProfile->photoURL);
            Session::put('gender', $hybridAuthUserProfile->gender);

            return View::make('profile_detail');

        } catch ( Exception $e ) {
			return View::make('error', array('messages' => $e->getMessage()));
        }

        return;
    }

}
