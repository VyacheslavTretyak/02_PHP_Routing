<?php

class Routing{
    public function __construct()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $pos = strpos($uri , '?');
        if($pos !== null){
            $request = substr($uri, $pos+1 );
            parse_str($request, $_GET);
        }
        if($pos == null)
        {
            $pos = strlen($uri);
        }
        $uri = substr($uri, 0, $pos);
        $routings = include 'routings.php';
        foreach ($routings as $patern=>$rout){
            if(preg_match("~^$patern$~", $uri)){
                $res = preg_replace("~$patern~", $rout, $uri, 1);
            }
        }
        if($res == false){
            $split = explode("/",$uri);
            $controller = ucfirst($split[1])."Controller";
            $action = ucfirst($split[2])."Action";
            $fileName = __DIR__."/controllers/".$controller.".php";
            if(file_exists($fileName)){
                require $fileName;
                $obj = new $controller;
                if(method_exists($obj, $action)){
                    echo "Action:";
                    $obj->$action();
                }else{
                    echo "<br>\"$action\" not found in $controller!<br>";
                }
            }else{
                echo "<br>\"$controller\" not found!<br>";
            }
            exit;
        }
      //  echo "result : $res<br>";
        $arr = explode('|',$res);
        $controller = ucfirst(array_shift($arr))."Controller";
        $action = ucfirst( array_shift($arr))."Action";
        $params = array();
        foreach($arr as $param){
            if(strpos($param, '=')!== null){
                $tmp = explode('=',$param);
                $params[$tmp[0]] = $tmp[1];
            }else{
                $params[] = $param;
            }
        }
        echo "Controller : $controller<br>";
        echo "Action : $action<br>";
        echo "Params : ";
        print_r($params);


    }

}