<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 19/7/18
 * Time: 11:53 AM
 */

namespace App\core;

use Klein\App;
use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Jenssegers\Agent\Agent;

class BaseController
{
    const TWIG_1 = 'twig1';
    const TWIG_2 = 'twig2';
    const VIEW_TEMPLATE_EXT = '.twig';
    /**
     * @var array To hold all variables which will be used in view
     */
    protected $dataForView = [
        'extra_css'=>[],    // css which for a special page, must be a absolute URL
        'extra_js'=>[]      // js which for a special page, must be a absolute URL
    ];
    protected $twigLoader = null;
    protected $debugMode = null;

    /**
     * Hooks before view rendered; function names array
     * @var array
     */
    public $hooksBefore = [
        'beforeRender'  =>null
    ];
    /**
     * Hooks after view rendered; function names array
     * @var array
     */
    public $hooksAfter = [
        'afterRender'   =>null
    ];

    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var ServiceProvider
     */
    protected $serviceProvider;

    /**
     * @var App
     */
    protected $appInstance;

    /**
     * The client agent
     * @var Agent
     */
    protected $clientAgent;

    /**
     * BaseController constructor.
     * @param Request $request
     * @param Response $response
     * @param ServiceProvider|null $serviceProvider
     * @param App|null $app
     */
    public function __construct(Request $request, Response $response, ServiceProvider $serviceProvider = null, App $app = null)
    {
        $this->request = $request;
        $this->response = $response;
        $this->serviceProvider = $serviceProvider;
        $this->appInstance = $app;

        /**
         * Init the browser/client agent instance
         */
        $this->clientAgent = new Agent();
        $this->dataForView['clientAgent'] = $this->clientAgent;
        /**
         * Inject the browser's type into view
         */
        $this->dataForView['isPhone'] = $this->clientAgent->isPhone();
        $this->dataForView['isTablet'] = $this->clientAgent->isTablet();
    }

    /**
     * Life circle function, actions to do before render method is called
     * @param null $param
     */
    public function beforeRender($param = null){

    }

    /**
     * Life circle function, actions to do after render method is called
     * @param null $param
     */
    public function afterRender($param = null){

    }

    /**
     * Render twig template
     * @param $filePath
     * @param array $hooksBefore
     * @param array $hooksAfter
     */
    public function render($filePath,$hooksBefore=[],$hooksAfter=[]){
        try{
            if(strpos($filePath, '/') === 0){
                // if the give file path start with /, then remove it
                $filePath = substr($filePath, 1);
            }
            if(strpos($filePath,self::VIEW_TEMPLATE_EXT) === false){
                // if no extension name, add it
                $filePath .= self::VIEW_TEMPLATE_EXT;
            }

            /**
             * Execute controller hooks function
             */
            if(!empty($hooksBefore)){
                $this->hooksBefore = array_merge($this->hooksBefore, $hooksBefore);
            }
            if(!empty($hooksAfter)){
                $this->hooksAfter = array_merge($this->hooksAfter, $hooksAfter);
            }
            foreach ($this->hooksBefore as $functionName=>$params) {
                $this->$functionName($params);
            }

            // Hook function is a good place to inject some general data into view
            echo $this->loadTemplateFile()->render($filePath, $this->dataForView);

            /**
             * Execute controller after hooks function
             */
            foreach ($this->hooksAfter as $functionName=>$params) {
                $this->$functionName($params);
            }
        }catch (\Exception $exception){
            if(env('DEV_MODE',false)){
                dump($exception->getMessage());
                dump($exception->getLine());
                dump($exception->getFile());
                dump($exception->getTraceAsString());
            }else{
                echo 'The page you request is not exist!';
            }
            exit(44);
        }
    }

    /**
     * Get twig
     * @return Environment
     */
    private function loadTemplateFile(){
        $loader = new FilesystemLoader(view_path());
        $this->debugMode = env('DEV_MODE', true);

        $options = [
            'debug' => $this->debugMode,
            'cache' => cache_path()
        ];
        $twig = new Environment($loader, $options);

        /**
         * This will allow the php function runnable in twig template
         */
        $twig->addExtension(new PhpFunctionExtension());

        if($this->debugMode){
            $twig->addExtension(new DebugExtension());
        }
        return $twig;
    }

    /**
     * Save user data in session
     * @param $data
     * @param $uuid
     */
    public function saveUserInSession($data, $uuid){
        $this->response->cookie('uuid',$uuid,time() + 3600,'/',url());
        session_set(env('SESSION_SEGMENT','_gekko_creation'), $uuid);
        session_set('user_data_array', $data);
    }
}