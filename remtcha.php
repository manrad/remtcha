<?php
/* 
 * 
 *   remtcha : Generates random characters based on language and unicode characters
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

$dpfc=225511;
$dpbgc=773311;
$bpfc = 554411;
$bpbgc = 334411;
$bpbgo = 2;
$dpbgo = 4;
$dpbgi = "variator0";
$bpbgi = "remb0";
// default foreground image for displaybox
$imgoverlay = "variator_overlay0";

switch($_SERVER['REQUEST_METHOD']){
    case 'GET': 
        if(isset($_GET["showrem"])){
            $allparam = $_GET["showrem"];
            remtchadiv($allparam);
            echo $allparam;
        }        
        break;
    default:
        break;
}

//echo $lang;

function isDuplicate($index,$chkarray,$randv){
    for($ix=0;$ix < $index;$ix++){
        if(($chkarray[$ix] === $randv)){
            return TRUE;
        }
    }
    return FALSE;
}

function getucrandom($ucarray){
    $padarray = [0,0,0,0,0,0,0,0,0,0,0,0];
    $randpadindexarr = [0,0,0,0,0,0,0,0,0,0,0,0];
    for($ix=0;$ix<12;$ix++){
        $flag =  TRUE;
        while($flag){
            $randval = rand(0,(count($ucarray)-1));
            if(!isDuplicate($ix,$padarray,$ucarray[$randval])){
                $randpadindexarr[$ix] = $randval;
                $padarray[$ix] = $ucarray[$randval];
                $flag=FALSE;
            }
        }
    }
    $_SESSION["randpadindex"] = $randpadindexarr;
    return $padarray;
}

function getkeyarray($padarray){
    $keyarray = [0,0,0,0,0];
    $randsequence = [0,0,0,0,0];
    for($ix = 0;$ix < 5;$ix++){
        $flag =  TRUE;        
        while($flag){
            $randval = rand(0,(count($padarray)-1));
            if(!isDuplicate($ix,$keyarray,$padarray[$randval])){
                $keyarray[$ix] = $padarray[$randval];
                $randsequence[$ix] = $randval;
                $flag=FALSE;
            }
        }
    }
    $_SESSION["randseq"] = $randsequence;
    return $keyarray;    
}


function remtchadiv($allparam){
    $paramarray = explode("__",$allparam);
    $dpfc = $paramarray[0];
    $dpbgc = $paramarray[1];
    $dpbgi = $paramarray[2].".png";
    $bpfc = $paramarray[3];
    $bpbgc = $paramarray[4];
    $bpbgo = $paramarray[5];
    $bpbgi = $paramarray[6].".png";
    $lang = $paramarray[7];
    $bpfco = $paramarray[8];
    // generate array
    $json = file_get_contents('json/minwoodbox.json');  
    $jsonobj = json_decode($json);
    
    $ucsymbols = $jsonobj->$lang->charandSymb;
    $_SESSION["ucsymbols"] = $ucsymbols;
    
    $ucarray = $jsonobj->$lang->unicodeNumbers;
    $_SESSION["ucarr"] = $ucarray;
    //var_dump($_SESSION["ucarr"]);
    
    $padarray = getucrandom($ucarray);
    $_SESSION["padarr"] = $padarray;
    //var_dump($_SESSION["padarr"]);
    
    $keyarray = getkeyarray($padarray);
    $_SESSION["keyarr"] = $keyarray;
    //var_dump($_SESSION["keyarr"]);
    /************ this is for image map ***********************/
    // for each pad array create a hash object
    $hashrecord = [];
    $rndpadindx = $_SESSION["randpadindex"];
    $hashstr = "testhas".rand(7,99999);
    for($ix = 0;$ix < count($padarray);$ix++){
        $hashstr = $hashstr.$ix;
        $sha1str = sha1($hashstr);
        $iy = $rndpadindx[$ix];
        $hashrecord[$ix] = array('id'=>$ix,'hashcode'=>$sha1str,'uc'=>$padarray[$ix],'symbol'=>$ucsymbols[$iy]);
    }
    $_SESSION["hasharray"] = $hashrecord;
    $_SESSION["commarga"] = $hashrecord;
    // generate key array from hashrecord with different index
    // format ix and hashcode
    $hashkey = [];
    $seqarray = $_SESSION["randseq"];
    $hashstr = "testhas".rand(7,99999);
    for($ix = 0;$ix < count($keyarray);$ix++){
        $randindexvalue = $seqarray[$ix];
        $hashstr = $hashstr.$ix.$randindexvalue;
        $sha1str = sha1($hashstr);
        $hashkey[$ix] = array('id'=>$ix,'sequenceid'=>$randindexvalue,'hashcode'=>$sha1str, 'symbol'=>$ucsymbols[$ix]);
    }
    $_SESSION["hashkeyarr"] = $hashkey;
}


?> 