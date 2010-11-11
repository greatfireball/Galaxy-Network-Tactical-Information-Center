<?php
class Tic_Language_Twig_Extension extends Twig_Extension {
    
    public function getLString($string) {
        return 'HITLER HITLER HITLER';
    }
    
    public function getFilters() {
        return array ('lang' => new Twig_Filter_Method ( $this, 'getLString' ) );
    }
    
    public function getName() {
        return 'TIC-Language';
    }
}