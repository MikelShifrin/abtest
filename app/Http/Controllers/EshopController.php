<?php
/**
 * Created by PhpStorm.
 * User: vivek
 * Date: 13-10-2017
 * Time: 19:15
 */

namespace App\Http\Controllers;


use App\Catalog\UserCatalog;
use App\Catalog\ElectronicCatalog;

use Illuminate\Http\Request;
use Illuminate\Session\Store;


class EshopController extends Controller
{
    private $user_catalog;

    public function __construct() {
       $user_catalog = new UserCatalog();
        $this->setUserCatalog($user_catalog);
    }

   /* /**
     * @return mixed
     */
    public function getUserCatalog()
    {
       return $this->user_catalog;
    }

    /**
     * @param mixed $user_catalog
     */
    public function setUserCatalog($user_catalog)
    {
       $this->user_catalog = $user_catalog;
    }



    public function login(Store $session, Request $request) {

        $username = $request->input('email');
        $password = $request->input('password');

<<<<<<< HEAD
    $this->getUserCatalog()->authenticate($username,$password);


      return view( 'welcome');
  }

    
=======
       $login = $this->getUserCatalog()->authenticate($username,$password);
//        echo $login;
        if($login){
            return view( 'welcome');
        } else {
            $return = "Invalid Credentials!";
            return view('login', ['return'=>$return]);
        }

    }
>>>>>>> ef6ae6a10d00b43169a5ec774be9fe2cd81f7775

}

?>