<?php
/* 
 * 
 *   bp_php : interface between remtcha php and python
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
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>
 * and open the template in the editor.
 */
include 'session_handler.php';
ini_set('session.use_strict_mode', 1);
my_session_start();
$bpvalue = "";
if(!isset($_SESSION["spectauser"])){
    
    
}
else
{
    die();    
}
switch($_SERVER['REQUEST_METHOD']){
    case 'GET': 
        if(isset($_GET["showrem"])){
            $allparam = $_GET["showrem"];
            $handledvalue = handleparam($allparam);
            $_SESSION["bp"] = $handledvalue;
            $_SESSION["bpv"] = $bpvalue;
            echo $handledvalue;            
        }        
        break;
    default:
        break;
}


function getfontfile($lang){
    $json = file_get_contents('json/minwoodbox.json');
    $jsonobj = json_decode($json);
    return $jsonobj->$lang->fontname;
}

function handleparam($param){
    $paramarray = explode("__",$param);
    $dpfc = $paramarray[0];
    $dpbgc = $paramarray[1];
    $dpbgi = $paramarray[2].".png";
    $bpfc = $paramarray[3];
    $bpbgc = $paramarray[4].".png";
    $bpbgo = $paramarray[5];
    $bpbgi = $paramarray[6].".png";
    $lang = $paramarray[7];
    $bpfco = $paramarray[8];
    $ttf = getfontfile($lang);
    
    $keystr = "";
    $padarray = [0,0,0,0,0,0,0,0,0,0,0,0];
    if(isset($_SESSION["padarr"])){
        $padarray = $_SESSION["padarr"];
    }
    for($ix=0; $ix < count($padarray); $ix++){
        $keystr = $keystr." ".$padarray[$ix];
    }
    $argstr = "bp ".$ttf." ".$dpfc." ".$dpbgc." ".$dpbgi." ".$bpfc." ".$bpbgc." ".$bpbgo." ".$bpbgi." ".$bpfco;
    // ensure you give the full path for python3 and the python file
    $command = "python3 bpactcode.py"." ".$argstr." ".$keystr;
    $bpvalue = $command;
    return shell_exec($command);
}


?> 
