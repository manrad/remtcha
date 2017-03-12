<?php
/* 
 * 
 *   verifykeys_post_handler : this file can be used to redirect based on user input
 *   - not used
 *   Copyright (C) 2017  Manivannan Radhakannan
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http:///www.gnu.org/licenses/>
 * and open the template in the editor.
 */
include 'session_handler.php';
ini_set('session.use_strict_mode', 1);
my_session_start();

switch($_SERVER['REQUEST_METHOD']){
    case 'POST':
        if(isset($_POST["clear"])){
            if(strcmp($_POST["clear"],"clear")==0){
                    // reset uc array
                    // reload home page
                unset($_SESSION["SequenceMapper"]);
                header("Location:index.php");
            }
            elseif(strcmp($_POST["clear"],"submit")== 0){
                if(isset($_POST["confirmdiv"])){
                    $allparam = $_POST["confirmdiv"];
                    echo verifykeys($allparam);
                }
            }
        }            
        break;
    default:
        break;
}


function verifykeys($allparam){
    // TBD compare with the hash value matching the hashcode and the unicode character
    if(strlen($allparam) > 0){
        $sequencemap = $_SESSION["SequenceMapper"];
        $seqcheck = true;
        for($ix = 0; $ix < count($sequencemap); $ix++){
            if($sequencemap[$ix] !== 1){
                $seqcheck=false;break;
            }
        }
        if($seqcheck){
            $_SESSION["result"] = "PASS";
            //header("Location:http://bbc.com");
        }
        else{
            $_SESSION["result"] = "FAIL";
            //header("Location:http://yahoo.com");
        }
        unset($_SESSION["SequenceMapper"]);
    }
    else{
        $_SESSION["result"] = "FAIL";
    }
}

function echoresult(){
        $pass = 0;
        if(strcmp($_SESSION["result"],"PASS") == 0){
            $pass = 1;
            
        }
        $resstr = "<div id=\"green\" style=\"margin-left:135px;width:70px;height:80px;height:90px;float:left;\">
        <img src=\"img/remtcha/green.png\">
         <b style=\"color:green;font-size:100%;\">Perfect!</b>
        </div>
        <div id=\"red\" style=\"width:70px;height:80px;height:90px;float:left;opacity:0.1;\">
            <img src=\"img/remtcha/red.png\">
            <b style=\"color:green;font-size:100%;\">OOPS !</b>
        </div>
        <div id=\"orang\" style=\"width:70px;height:80px;height:90px;float:left;\">
            <img src=\"img/remtcha/orange.png\">
            <b style=\"color:green;font-size:100%;\">Retry</b>
        </div>        
        </div>";
        echo $resstr;
}

?>

<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8"/>
  <title>Remtcha Verification</title>
  <link rel="stylesheet" type="text/css" href="./index.css"> 
  <script type="application/javascript">     
      function demopage(){
    //     alert("redirect to demo page");
}
  </script>
 </head>
 <body>
     
    <?php echoresult(); ?>
    <b onclick="demopage()">Retry?</b>
   
     
 </body>
</html>