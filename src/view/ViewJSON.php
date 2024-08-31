<?php

class ViewJSON {
   protected $content;

   public function __construct(Router $router) {
    $this->router = $router;
    $this->content = null;
   }
   
   public function render() {
    echo $this->content;
   }

   public function prepareJSON($data) {
    $this->content = json_encode($data, JSON_UNESCAPED_UNICODE);
   }

    
}

?>
