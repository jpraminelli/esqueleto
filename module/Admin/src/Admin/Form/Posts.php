<?php
namespace Admin\Form;
use Zend\Form\Form;

class Posts extends Form
{
    public function __construct() {
        parent::__construct('post');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action' , 'save');
        
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
            'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'attributes' => array(
            'type' => 'text',
            ),
            'options' => array(
            'label' => 'Título',
            ),
       ));
        
        $this->add(array(
            'name' => 'description',
            'attributes' => array(
            'type' => 'textarea',
            ),
            'options' => array(
            'label' => 'Texto do post',
            ),
       ));
        $this->add(array(
           'name' => 'submit',
            'attributes' => array(
            'type' => 'submit',
            'value' => 'Enviar',
            'id' => 'submitbutton',
            ),
       ));


    }
}
?>
