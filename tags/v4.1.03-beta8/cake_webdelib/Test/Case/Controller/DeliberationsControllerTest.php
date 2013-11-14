<?php
/**
* Code source de la classe Histochoixcer93Test.
*
* PHP 5.3
*
* @package app.Test.Case.Model
* @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
*/
App::uses('Circuit', 'Cakeflow.Controller');

/**
* Classe DeliberationsTest.
*
* @package app.Test.Case.Controller
* 
*/
class DeliberationsTest extends DeliberationsController {
       
    // Les fixtures de plugin localisé dans /app/Plugin/Blog/Test/Fixture/
    public $fixtures = array('plugin.cakeflow.circuit');
    public $Circuit;

}

class DeliberationsControllerTest extends CakeAppControllerTestCase {

        public function testFunction() {

        }
        
        public function testSomething() {
        // ClassRegistry dit au model d'utiliser la connection à la base de données test
        $this->addIntoCircuit = ClassRegistry::init('Blog.BlogPost');

        // faire des tests utiles ici
        $this->assertTrue(is_object($this->BlogPost));
    }

}




?>