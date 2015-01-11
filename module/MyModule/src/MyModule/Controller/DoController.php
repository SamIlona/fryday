<?php

namespace MyModule\Controller;
 
use Zend\Mvc\Controller\AbstractActionController,
    Zend\Console\Request as ConsoleRequest;
 
class DoController extends AbstractActionController
{
 
    public function donowAction()
    {
        $request = $this->getRequest();
 
        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$request instanceof ConsoleRequest){
            throw new \RuntimeException('You can only use this action from a console!');
        }
 
        // Get system service name  from console and check if the user used --verbose or -v flag
        // $doname   = $request->getParam('doname', false);
        // $verbose     = $request->getParam('verbose');
 
        // $shell = "ps aux";
        // if ($doname){
        //     $shell .= " |grep -i $doname ";
        // }
        // $shell .= " > /Users/abdulmalikikhsan/www/success.txt ";
        // //execute...
        // system($shell, $val);

        while(true){
            echo 'hi';
        }
 
        // if(!$verbose){
        //     echo "Process listed in /Users/abdulmalikikhsan/www/success.txt \r\n";
        // }else{
        //     $file = fopen('/Users/abdulmalikikhsan/www/success.txt',"r");
 
        //     while(! feof($file)){
        //         $listprocess = trim( fgets($file) );
 
        //         echo $listprocess."\r\n";
        //     }
        //     fclose($file);
        // }
    }
}