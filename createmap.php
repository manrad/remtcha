<?php
/* 
 * 
 *   createmap : creates image map for the symbols and characters
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
    if(isset($_GET["retimgmap"])){
            echo returnmap();
        }        
        break;
    default:
        die;
        break;
}


function returnmap(){
    
    if(isset($_SESSION["hasharray"])){
        $hashrecord = $_SESSION["hasharray"];
    }
    $mapstr =  "<map name=\"mymap\" >";
        $left = 0; $top = 0;
        for($ix = 0;$ix < count($hashrecord);$ix++){
                $right = $left + 70;
                $bottom = $top + 100;
                $mapstr = $mapstr . "<area id= \"a".$ix."\" shape=\"rect\" coords=\"".$left.",".$top.",".$right.",".$bottom."\"  onclick=\"mapped(this)\" alt=\"".$hashrecord[$ix]["hashcode"]."\">";
                $left = $left + 70;
                
                if($left > 240){
                    $left = 0;$top = $top+100;
                }
        }
        $mapstr = $mapstr."</map>";
return $mapstr;
}


?> 