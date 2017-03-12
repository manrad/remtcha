<?php
/* 
 * 
 *   verifykeys : validates the symbols pressed by the user and confirms the response
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
    case 'GET':
        if(isset($_GET["confirmdiv"])){
            $allparam = $_GET["confirmdiv"];
            echo verifykeys($allparam);
        }            
        break;
    default:
        break;
}



function verifykeys($allparam){
    // TBD compare with the hash value matching the hashcode and the unicode character
    $result = "OOPS!";
    if(strlen($allparam) > 0){
        $sequencemap = $_SESSION["SequenceMapper"];
        $seqcheck = true;
        for($ix = 0; $ix < count($sequencemap); $ix++){
            if($sequencemap[$ix] !== 1){
                $seqcheck=false;break;
            }
        }
        if($seqcheck){
            $result = "Perfect!";
        }
        unset($_SESSION["SequenceMapper"]);
    }
    return $result;
}


?>
