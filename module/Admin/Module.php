<?php

namespace Admin;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    /**
     * executado no bootstrap do modulo
     * @param type $e
     */
    public function onBootstrap($e){
        $moduleManager = $e->getApplication()
                ->getServiceManager()
                ->get('modulemanager');
        
        $sharedEvents = $moduleManager->getEventManager()
                ->getSharedManager();
        
        //adiciona eventos ao modulo
        $sharedEvents->attach(
            'Zend\Mvc\Controller\AbstractActionController',
            \Zend\Mvc\MvcEvent::EVENT_DISPATCH,
            array($this, 'mvcPreDispatch'),
            100
  
         );
    }
    
    /**
     * verifica se precisa fazer a autorização do aceso
     */
    public function mvcPreDispatch($event){
        $di = $event->getTarget()->getServiceLocator();
        $routeMatch = $event->getRouteMatch();
        $moduleName = $routeMatch->getParam('module');
        $controllerName = $routeMatch->getParam('controller');
        $actionName = $routeMatch->getParam('action');
        
        $authService = $di->get('Admin\Service\Auth');
        
        
        if(! $authService->authorize($moduleName,$controllerName,$actionName)){
            throw new \Exception('Acesso Restrito');
        }
        return true;
    }

}