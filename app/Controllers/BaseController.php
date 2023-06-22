<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{

    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['form', 'url'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        $this->session = \Config\Services::session();
    }

    public function validate_form($data, $str):bool{
        $validation = \Config\Services::validation();
        return $validation->run($data, $str);
    }

    public function baseHomeView($body, $data = []){
        return view('common/header', ['title' => 'Home', 'cssPath' => 'css/main.css', 'jsPath' => 'js/script.js'])
        . view('common/menu')
        . view($body, $data)
        . view('common/footer');
    }

    public function baseGameView($body, $data = []){
        return view('common/header', ['title' => 'Home', 'cssPath' => 'css/main.css', 'jsPath' => 'js/script.js'])
        . view('signup/game/menu')
        . view($body, $data)
        . view('common/footer');
    }

    public function delteFiles($folder ,$file){
        unlink('./images/storage/'.$folder.'/'.$file);
        unlink('./images/thumb/'.$folder.'/'.$file);
        unlink('./images/publish/'.$folder.'/'.$file);
    }

    public function resizeImage($fileName, $place, $path, $width, $height){
        $imageService = \Config\Services::image('gd');
        $imageService->withFile('./images/storage/'.$path.'/'.$fileName)
            ->fit($width, $height, 'center')
            ->save('./images/'.$place.'/'.$path.'/'.$fileName);
    }

    public function upload_image($path, $image){
        $randName = $image->getRandomName();
        //dd('./images/storage/'.$path);
        $imageStatus = $image->move('./images/storage/'.$path, $randName);
        if($imageStatus){
            $this->resizeImage($randName, 'thumb', $path, 250, 250);
            $this->resizeImage($randName, 'publish', $path, 1650, 1250);
        }
        return $randName;
    }

}
