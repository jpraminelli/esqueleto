<?php
namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Application\Model\Post;
use Admin\Form\Posts;

class IndexController extends ActionController{
    
    public function saveAction(){
        $form = new Posts();
        $request = $this->getRequest();
        
        if($request->isPost()){
            $post = new Post();
            
            $form->setInputFilter($post->getInputFilter());
            
            //populate
            $form->setData($request->getPost());
            
            if($form->isValid()){
                $data = $form->getData();
                //retira o botao save (submit)
                unset($data['submit']);
                
                //data
                $data['post_date'] = date('Y-m-d H:i:s');
                
                //preenche os dados do objeto Post com os dados do formulario
                $post->setData($data);
                
                //salva
                $saved = $this->getTable('Application\Model\Post')->save($post);
                
                return $this->redirect()->toRoute('home');
            }
        }
        
        //pega o parametro
        $id = (int) $this->params()->fromRoute('id',0);
        
        if($id > 0){
            //get dados do banco para popular o form
            $post = $this->getTable('Application\Model\Post')->get($id);
            //popula o fomr com os dados
            $form->bind($post);
            
            //muda o texto do botao suvmit
            $form->get('submit')->setAttribute('value', 'Editar');
            
            
        }
        return new ViewModel(array(
            'form' => $form
         ));
    }
    
    public function deleteAction(){
        //pega o parametro
        $id = (int) $this->params()->fromRoute('id',0);
        
        if($id == 0){
            throw  new \Exception('Codigo obrigatorio');
        }
        
        $this->getTable('Application\Model\Post')->delete($id);
        return $this->redirect()->toRoute('home');
    }
}
?>
