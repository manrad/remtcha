<?php
/* 
 * 
 *   dp_php : interface between remtcha php and python to create display pad
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


$dpvalue = "";
switch($_SERVER['REQUEST_METHOD']){
    case 'GET': 
    if(isset($_GET["showrem"])){
            $allparam = $_GET["showrem"];
            $handledvalue = handledpparam($allparam);
            $_SESSION["dp"] = $handledvalue;
            $_SESSION["dpv"] = $dpvalue;
            echo $handledvalue;
        }        
        break;
    default:
        die;
        break;
}

function getfontfile($lang){
    $json = file_get_contents('json/minwoodbox.json');
    $jsonobj = json_decode($json);
    // if the characters are english and latin return a different array with the utf numbers
    return $jsonobj->$lang->fontname;
}

function handledpparam($param){
    $paramarray = explode("__",$param);
    //22_55_11_6__77_33_11_4__variator0__33_44_11_6__variator_overlay0__33_44_11_2__remb0__%E0%AE%A4%E0%AE%AE%E0%AE%BF%E0%AE%B4%E0%AF%8D
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
    $keyarray = [2,3,4,5,7];
    if(isset($_SESSION["keyarr"])){
        $keyarray = $_SESSION["keyarr"];
    }
    $argstr = "dp ".$ttf." ".$dpfc." ".$dpbgc." ".$dpbgi." ".$bpfc." ".$bpbgc." ".$bpbgo." ".$bpbgi." ".$bpfco;
    $keystr = $keyarray[0]." ".$keyarray[1]." ".$keyarray[2]." ".$keyarray[3]." ".$keyarray[4];
    // ensure you give the full path for python3 and the python file
    $command = "python3 actcode.py"." ".$argstr." ".$keystr;
    $dpvalue = $command;
    //exec($command,$arr,$ix);
    //return serialize($arr);
    //return shell_exec($command);
    return system($command,$a);
}


?> 
