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


switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        // echo matching keys        
        if(isset($_GET["hashcode"])){
                $hash = $_GET["hashcode"];
                echo returnmap($hash);
            }        
        break;
    default:
        break;
}


function returnmap($hashcode){
    $matchsymbol = "";
    $hashrecord = $_SESSION["hasharray"];
    if(!isset($_SESSION["SequenceMapper"])){
        $_SESSION["SequenceMapper"] = [0,0,0,0,0];
    }
    $sequencemap = $_SESSION["SequenceMapper"];
    $seqarray = $_SESSION["randseq"];
    //$seqarrayprint = "_seqarr_".$seqarray[0]."__".$seqarray[1]."__".$seqarray[2]."__".$seqarray[3]."__".$seqarray[4]."\n";
    $seqmatcher = "";
    for($ix = 0;$ix < count($hashrecord);$ix++){
        //$matchsymbol = $matchsymbol."__".$hashrecord[$ix]["hashcode"];
        $temphashcode = $hashrecord[$ix]["hashcode"];
        
        if(hash_equals($hashcode,$temphashcode)){
            $matchsymbol = $hashrecord[$ix]["symbol"];
            //$seqmatcher = $seqmatcher."__".$ix."__";
            $zix = retindex($sequencemap);
            // get current index of '0' in sequence map
            if($zix < count($sequencemap)){
                for($iy=0;$iy < count($seqarray);$iy++){
                    if(($ix === $seqarray[$iy]) && ($zix === $iy)){
                        $sequencemap[$zix] = 1;
                    }
                    elseif (($ix === $seqarray[$iy]) && ($zix !== $iy)) {
                        $sequencemap[$zix] = 2;
                    }
                }
            }
            else{}
        }
    }
    $_SESSION["SequenceMapper"] = $sequencemap;
    //$seqmapstr = "_seqmaptstr_".$sequencemap[0]."_".$sequencemap[1]."_".$sequencemap[2]."_".$sequencemap[3]."_".$sequencemap[4];    
    return $matchsymbol;
}

function retindex($sarr){
    for($ix=0;$ix < count($sarr);$ix++){
        if($sarr[$ix] === 0) {
            return $ix;
        }
    }
    return count($sarr) + 1;
}

?>