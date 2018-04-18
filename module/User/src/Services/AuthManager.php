<?php
namespace User\Services;

use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Session\SessionManager;


class AuthManager
{
    private $authService;
    
    private $sessionManager;

    public function __construct(AuthenticationService $authService, SessionManager $sessionManager) 
    {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
    }
    
    public function login($useremail, $password)
    {   
        if ($this->authService->getIdentity()!=null) {
            throw new \Exception('Already logged in');
        }
        
        $authAdapter = $this->authService->getAdapter();
        $authAdapter->_useremail = $useremail;
        $authAdapter->_password = $password;
        $result = $this->authService->authenticate();

        if ($result->getCode()==Result::SUCCESS) {
            $this->sessionManager->rememberMe(60*60*24*30); // 30 jours
        }
        
        return $result;
    }
    
    public function logout()
    {
        if ($this->authService->getIdentity()==null) {
            throw new \Exception('The user is not logged in');
        }
        
        $this->authService->clearIdentity();               
    }
    
    public function getUserData()
    {
        if ($this->authService->getIdentity()==null) {
            throw new \Exception('The user is not logged in');
        }
        
        return $this->authService->getIdentity();               
    }
    
    public function updateUserEmailOnSession($email, $password)
    {

        // $password = $this->getUserData()['password'];

        $this->logout();
        $result = $this->login($email, $password);

        if ($result->getCode() != Result::SUCCESS) {
            throw new \Exception('FUCK : ' . $result->getMessages()[0]);
        }


/*
        if ($this->authService->getIdentity()==null) {
            throw new \Exception('The user is not logged in');
        }
        
        $authAdapter = $this->authService->getAdapter();
        $authAdapter->_useremail = $email;
        $authAdapter->_password = $this->getUserData()['password'];
        $result = $this->authService->authenticate();

        if ($result->getCode()==Result::SUCCESS) {
            $this->sessionManager->rememberMe(60*60*24*30); // 30 jours
        }*/
    }

    public function filterAccess($controllerName, $actionName)
    {
        // Determine mode - 'restrictive' (default) or 'permissive'. In restrictive
        // mode all controller actions must be explicitly listed under the 'access_filter'
        // config key, and access is denied to any not listed action for unauthorized users. 
        // In permissive mode, if an action is not listed under the 'access_filter' key, 
        // access to it is permitted to anyone (even for not logged in users.
        // Restrictive mode is more secure and recommended to use.
        $mode = isset($this->config['options']['mode'])?$this->config['options']['mode']:'restrictive';
        if ($mode!='restrictive' && $mode!='permissive')
            throw new \Exception('Invalid access filter mode (expected either restrictive or permissive mode');
        
        if (isset($this->config['controllers'][$controllerName])) {
            $items = $this->config['controllers'][$controllerName];
            foreach ($items as $item) {
                $actionList = $item['actions'];
                $allow = $item['allow'];
                if (is_array($actionList) && in_array($actionName, $actionList) ||
                    $actionList=='*') {
                    if ($allow=='*')
                        return true; // Anyone is allowed to see the page.
                    else if ($allow=='@' && $this->authService->hasIdentity()) {
                        return true; // Only authenticated user is allowed to see the page.
                    } else {                    
                        return false; // Access denied.
                    }
                }
            }            
        }
        
        // In restrictive mode, we forbid access for unauthorized users to any 
        // action not listed under 'access_filter' key (for security reasons).
        if ($mode=='restrictive' && !$this->authService->hasIdentity())
            return false;
        
        // Permit access to this page.
        return true;
    }
}