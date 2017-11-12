<?php
/**
 * Created by PhpStorm.
 * User: vivek
 * Date: 13-10-2017
 * Time: 19:15
 */

namespace App\Http\Controllers;

use App\IdentityMap\IdentityMap;
use App\Mapper\Mapper;
use App\Catalog\ClientLogCatalog;
use App\Catalog\UserCatalog;
use App\Catalog\ElectronicCatalog;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Session;


class EshopController extends Controller
{
    //private $user_catalog;
    //private $client_log_catalog;
    //private $electronic_catalog;

    private $mapper;

    public function __construct() {

        $this->setMapper(Mapper::Instance());
        //$user_catalog = new UserCatalog();
        //$this->setUserCatalog($user_catalog);

        //$client_log_catalog = new ClientLogCatalog();
        //$this->setClientLogCatalog($client_log_catalog);

        //$electronic_catalog = new ElectronicCatalog();
        //$this->setElectronicCatalog($electronic_catalog);
    }

    /**
     * @return mixed
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @param mixed $mapper
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
    }



    public function login(Store $session, Request $request) {


        $username = $request->input('email');
        $password = $request->input('password');
        $user_type = $request->input('asAdmin');
        if($user_type=="on") {
            $user_type = "admin";
        } else {
            $user_type = "user";
        }

       $login = $this->getMapper()->getUserCatalog()->authenticate($username,$password,$user_type);
        if($login==false){
            $return = "Invalid Credentials!";
            return view('login', ['return'=>$return]);
        } else {
            $user_id = $login['user_id'];
            session_start();
            $_SESSION['user'] = $login;
            $_SESSION['email'] = $login['email_id'];

            if($user_type=="admin") {
                $_SESSION['user_type'] = "admin";
                $_SESSION['ElectronicsIdAddInternalCounterInitial'] = 10000000;
                $_SESSION['ElectronicsIdAddInternalCounter'] = 10000000;
                return view( 'loginWelcome');
            } else {
                $_SESSION['user_type'] = "user";
                return view('welcomeUser');
            }
        }
    }

    public function registerUser(Store $session, Request $request) {

        $username       = $request->input('email');
        $password       = $request->input('password');
        $firstName      = $request->input('firstName');
        $lastName       = $request->input('lastName');
        $addressLineOne = $request->input('addressLineOne');
        $addressLineTwo = $request->input('addressLineTwo');
        $telephone      = $request->input('telephone');

        $user = $this->getMapper()->getUserCatalog()->createNewUser($username,$password,$firstName,$lastName,$addressLineOne,$addressLineTwo,$telephone);
        //print_r($user);
        $this->mapper->getUserTDG()->addNewUser($user);

        $return = "Please Login in!";
        return view('login', ['return'=>$return]);
    }

    public function logout()
    {
        session_start();
        session_destroy();

        return view( 'login');
    }

    public function addElectronicItem(Store $session, Request $request)
    {
        session_start();

        //$brandName   = $request->input('brandName');
        //$modelNumber = $request->input('modelNumber');
        //$price       = $request->input('price');
        //$weight      = $request->input('weight');
        //$displaySize = $request->input('displaySize');


        //$brandName=
       // $ret = Mapper::Instance()->getElectronicCatalog()->additem($request);
        $ret = Mapper::Instance()->createElectronics($request); // pass the request to mapper for adding electronics
        $return="Deatils added successfully";
        return view( 'welcome',['return'=>$return]);
    }

    public function viewInventory($type)
    {
        session_start();

        if(isset($_SESSION['singletonMap'])){
            $singletonIdMap = $_SESSION['singletonMap'];
            echo spl_object_hash ($singletonIdMap);

        } else {
            $singletonIdMap = IdentityMap::Instance();
            $_SESSION['singletonMap'] = $singletonIdMap;
        }

//        $ret = $this->mapper->getElectronicCatalog()->viewInventory($type);
        if($type=='1')
        {
            $ret = Mapper::Instance()->findAllDesktop();                             //Message to Mapper to get all desktops
            return view( 'view.viewInventoryDesktop',['ret'=>$ret]);      //Return to view
        }
        elseif ($type=='2')
        {
            $ret = $this->mapper->findAllMonitor();                             //Message to Mapper to get all monitors
            return view( 'view.viewInventoryMonitor',['ret'=>$ret]);      //Return to view
        }
        elseif ($type=='3')
        {
            $ret = $this->mapper->findAllLaptop();                             //Message to Mapper to get all laptops
            return view( 'view.viewInventoryLaptop',['ret'=>$ret]);      //Return to view
        }
        elseif($type=='4')
        {
            $ret = $this->mapper->findAllTablet();                             //Message to Mapper to get all tablets
            return view( 'view.viewInventoryTablet',['ret'=>$ret]);      //Return to view
        }
    }

    public function deleteViewInventory($type) {       
        session_start();

        $ret = $this->getMapper()->getElectronicCatalog()->deleteInventory($type);
        if($type=='1'){
            $ret = Mapper::Instance()->findAllDesktop();  
        return view( 'delete.deleteInventoryDesktop',['ret'=>$ret]);
        } elseif ($type=='2') {
            $ret = Mapper::Instance()->findAllMonitor();
            return view( 'delete.deleteInventoryMonitor',['ret'=>$ret]);
        } elseif ($type=='3') {
            $ret = Mapper::Instance()->findAllLaptop();
            return view( 'delete.deleteInventoryLaptop',['ret'=>$ret]);
        } elseif($type=='4') {
            $ret = Mapper::Instance()->findAllTablet();
            return view( 'delete.deleteInventoryTablet',['ret'=>$ret]);
        } 

    }
    
    /*
    delete function gets it's parameters from deleteInventoryDesktop.blade.php view
    it is redirected here through a post request in web.php
    */
    public function deleteElectronicItem(Store $session, Request $request)
    {
        session_start();

        $ret = Mapper::Instance()->deleteElectronicItem($request);
        $return="Item Deleted Successfully";
        return view( 'welcome',['return'=>$return]);
    }


    public function modifyInventory($type) {
        session_start();

//        $ret = $this->mapper->getElectronicCatalog()->deleteInventory($type);
        if($type=='1'){
            echo spl_object_hash ( IdentityMap::Instance());
            $ret = Mapper::Instance()->findAllDesktop();                             //Message to Mapper to get all desktops
            return view( 'modify.modifyInventoryDesktop',['ret'=>$ret]);      //Return to view
        } elseif ($type=='2') {
            $ret = Mapper::Instance()->findAllMonitor();
            return view( 'modify.modifyInventoryMonitor',['ret'=>$ret]);
        } elseif ($type=='3') {
            $ret = Mapper::Instance()->findAllLaptop();
            return view( 'modify.modifyInventoryLaptop',['ret'=>$ret]);
        } elseif($type=='4') {
            $ret = Mapper::Instance()->findAllTablet();
            return view( 'modify.modifyInventoryTablet',['ret'=>$ret]);
        }
    }

    public function modifyElectronics(Request $request,$type) {
        session_start();
        if($type=='1'){
            echo spl_object_hash (IdentityMap::Instance());
            $desktop = $this->mapper->modifyElectronics($request,$type);                      //
            $return="Desktop Updated Successfully";
            return view( 'welcome',['return'=>$return]);
        }
         elseif($type=='2'){

             $monitor = $this->mapper->modifyElectronics($request,$type);                      //
             $return="Monitor Updated Successfully";
             return view( 'welcome',['return'=>$return]);
        }
        elseif($type=='3'){
            $laptop = $this->mapper->modifyElectronics($request,$type);                      //
            $return="Laptop Updated Successfully";
            return view( 'welcome',['return'=>$return]);
        }
        elseif($type=='4'){
            $tablet = $this->mapper->modifyElectronics($request,$type);                      //
            $return="Tablet Updated Successfully";
            return view( 'welcome',['return'=>$return]);
        }
    }

    public function commit() {
        session_start();

        $this->mapper->commit();

        return view('loginWelcome');



    }

    }



?>
