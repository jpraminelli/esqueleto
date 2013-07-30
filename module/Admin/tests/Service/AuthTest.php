<?php
namespace Admin\Service;

use DateTime;
use Core\Test\ServiceTestCase;
use Admin\Model\User;
use Core\Model\EntityException;
use Zend\Authentication\AuthenticationService;

class AuthTest extends ServiceTestCase{
    
    public function testAuthenticateWithoutParams(){
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $authService->authenticate();
    }
    
    public function testAuthenticateEmptyParams(){
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $authService->authenticate(array());
    }
    
    public function testAuthenticateInvalidParameters(){
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $authService->authenticate(array(
            'username' => 'invalid', 'password' => 'invalid')
        );

    }
    
    public function testAuthenticateInvalidPassord(){
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $user = $this->addUser();
        $authService->authenticate(array(
            'username' => $user->username, 'password' => 'invalida')
        );
    }
    
    public function testAuthenticateValidParams(){
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $user = $this->addUser();
        $result = $authService->authenticate(array(
                        'username' => $user->username, 'password' => 'apple')
        );
        
        $this->assertTrue($result);
        
        //teste de autenticação
        $auth = new AuthenticationService();
        $this->assertEquals($auth->getIdentity(),$user->username);
        
        //verifica se o usuario foi salvo na sessao
        $session = $this->serviceManager->get('Session');
        $savedUser = $session->offsetGet('user');
        $this->assertEquals($user->id, $savedUser->id);
    }
    
    //limpaa autenticação depois de cada teste
    public function tearDown() {
        parent::tearDown();
        $auth = new AuthenticationService();
        $auth->clearIdentity();
    }
    
    //teste do logout
    public function testLogout(){
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $user = $this->addUser();
        $result = $authService->authenticate(
                array('username' => $user->username, 'password' => 'apple')
        );
        
        $this->assertTrue($result);
        
        $result = $authService->logout();
        $this->assertTrue($result);
        
        //verifica se removeu a identidade da autenticação
        $auth = new AuthenticationService();
        $this->assertNull($auth->getIdentity());
        
        //verifica se o usuario foi removido da sessao
        $session = $this->serviceManager->get('Session');
        $savedUser = $session->offsetGet('user');
        $this->assertNull($savedUser);
    }
    
    private function addUser(){
        $user = new User();
        
        $user->username = 'steve';
        $user->password = md5('apple');
        $user->name = 'Steve <b> Jobs</b>';
        $user->valid = 1;
        $user->role = 'admin';
        
        $saved = $this->getTable('Admin\Model\User')->save($user);
        return $saved;
    }
}
?>
