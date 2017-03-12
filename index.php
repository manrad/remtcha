<?php
/* 
 * 
 *   index.php : Landing Page for Remtcha
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

my_session_regenerate_id();
my_session_start();




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
    for($ix=0;$ix<12;$ix++){
        $flag =  TRUE;
        while($flag){
            $randval = rand(0,(count($ucarray)-1));
            if(!isDuplicate($ix,$padarray,$ucarray[$randval])){
                $padarray[$ix] = $ucarray[$randval];
                $flag=FALSE;
            }
        }
    }
    return $padarray;
}

function getkeyarray($padarray){
    $keyarray = [0,0,0,0,0];
    for($ix = 0;$ix < 5;$ix++){
        $flag =  TRUE;
        while($flag){
            $randval = rand(0,(count($padarray)-1));
            if(!isDuplicate($ix,$keyarray,$padarray[$randval])){
                $keyarray[$ix] = $padarray[$randval];
                $flag=FALSE;
            }
        }
    }
    return $keyarray;    
}

    
/*
 *  Showcolordiv
 *  shows the color option with different rgb combination
 *  4 * 255 color options
 */
function showcolordiv(){
    $json = file_get_contents('json/color.json');  
    $jsonobj = json_decode($json);
    $colorarr = $jsonobj->colorlist;
    //var_dump($colorarr);
    $str = " <div id=\"colorshow\">";
    for ($ix=0;$ix < count($colorarr);$ix++){
        $hexcolor = $colorarr[$ix];
        $ir = hexdec($hexcolor[1].$hexcolor[2]);
        $ig = hexdec($hexcolor[3].$hexcolor[4]);
        $ib = hexdec($hexcolor[5].$hexcolor[6]);
        $str = $str."<div onclick=\"printcolor(this)\" id=".$ir."_".$ig."_".$ib." style=\"color:".$hexcolor.";font-size:4px;height:35px;margin:auto;float:left;width:35px;background-color:".$hexcolor.";\"></div>";
    }
    $str = $str."</div>";
    return $str;
}

/*
 * opacitydiv
 * Shows opacity values from 0.1 to 1
 */
function opacitydiv(){
    $str = " <div id=\"opaqshow\" >";
    for($ix = 1; $ix < 11;$ix++){
        $str = $str."<div onclick=\"printopacity(this)\" style=\"border: solid grey 2px;float:left;width:10%;height:33px;background-color:grey;opacity:".($ix/10).";\"></div>";
    }
   $str = $str."</div>";
   return $str;    
}

/*
 * variator
 * shows the background image options for the display box
 * Images should be in the same folder(or you can change it)
 * Change the max index value $ix to match the images you have in the same folder
 */
function variator(){
    $dir = "img/dp/background/";
    $files1 = scandir($dir);
    $filecount = count($files1);
    $str = "<div id=\"variator\" style=\"border:solid grey 2px;width:100%;height:50px;overflow:auto;\">";
    for($ix = 2; $ix < $filecount; $ix++){
        $str = $str."<img onclick=\"printimage(this)\" style=\" width:300px;height:60px;border:solid grey 2px;\" src=\"".$dir.$files1[$ix]."\">";
    }
    $str = $str."</div>";
    return $str;
}

/*
 * variator_overlay
 * shows the foreground image masking the characters shown in the display box
 * Images should be in the same folder(or you can change it)
 * Change the max index value $ix to match the images you have in the same folder
 */
function variator_overlay(){
    $dir = "img/dp/foreground/";
    $files1 = scandir($dir);
    $filecount = count($files1);
    $str = "<div id=\"variator_overlay\" style=\"border:solid grey 2px;width:100%;height:50px;overflow:auto;\">";
    for($ix = 2; $ix < $filecount;$ix++){
        $str = $str."<img onclick=\"printimage(this)\" style=\"border: solid grey 2px;border: solid grey 2px;\" src=\"".$dir.$files1[$ix]."\">";
    }
    $str = $str."</div>";
    return $str;
}

/*
 * blockpad_bg
 * shows the background image for blockpad
 * Add images to the same folder and change the max index value accordingly
 */
function blockpad_bg(){
    $dir = "img/bp/";
    $files1 = scandir($dir);
    $filecount = count($files1);
    $str = "<div id=\"blockpad_bg\" style=\"border:solid grey 2px;width:100%;height:auto;overflow:auto;\">";
    for($ix = 2; $ix < $filecount;$ix++){
        $str = $str."<img onclick=\"printimagebp(this)\" style=\"border: solid grey 2px;\" src=\"".$dir.$files1[$ix]."\">";
    }
    $str = $str."</div>";
    return $str;
}

/*
 * showlanglist
 * Gets the symbols or language characters from the array based on language selected
 * refer to the json file for more details.
 */
function showlanglist(){
    $json = file_get_contents('json/minwoodbox.json');
    $jsonobj = json_decode($json);
    return $jsonobj->langlist;
}

/*
 * preparelangdiv
 * prepare and displays the list of language supported in the json file
 * refer to the json file for more details.
 */
function preparelangdiv(){
    $langarray = showlanglist();
    $strval = "<div id=\"langinfo\" style=\"margin:auto;background-color:grey;color:white;font-size:120%;\">";
    for($ix=0;$ix < count($langarray);$ix++){
        $strval = $strval."<div style=\"margin-right:10px;float:left;\" onclick=\"getstringfromfile(this)\">".$langarray[$ix]."</div>";
    }
    $strval = $strval."</div>";
    return $strval;
}


/*
 * randfontsize
 * return random font size between the range specified 
 */
function randomfontsize($minfontsize,$maxfontsize){
    return rand($minfontsize,$maxfontsize);
}

/*
 * randommargintop
 * return random margin top for the font element
 */
function randommargintop($min,$max){
    return rand($min,$max);
}

/*
 * randommarginbottom
 * return random margin bottom for the font element
 */
function randommarginbottom($min,$max){
    return rand($min,$max);
}

/*
 * randommarginleft
 * return random margin left for the font element
 */
function randommarginleft($min,$max){
    return rand($min,$max);
}

/*
 * randommarginright
 * return random margin left for the font element
 */
function randommarginright($min,$max){
    return rand($min,$max);
}

function showRemtchaDiv(){
    $parentdiv = 
            "<div id=\"parentdiv\" style=\"width:100%;text-align:center;margin:auto;padding-bottom:10px;\"><b style=\"font-size:150%;\">REMTCHA</b>    
            <div id=\"captchadiv\" action=\"/verifykeys.php\" method=\"POST\">
                <img id=\"blockrow\" src=\"dp_php.php?showrem=250_128_114_6__77_33_11_4__variator0__33_44_11_6__variator_overlay0__105_105_105_9__remb0__%E0%AE%A4%E0%AE%AE%E0%AE%BF%E0%AE%B4%E0%AF%8D__224_255_255_9\" alt=\"s\">
                <div id=\"textmsg\"> Match the symbols you see in the box above, by clicking on symbols in the blockpad below</div>
                <img id=\"block_pad\" src=\"bp_php.php?showrem=250_128_114_6__77_33_11_4__variator0__33_44_11_6__variator_overlay0__105_105_105_9__remb0__%E0%AE%A4%E0%AE%AE%E0%AE%BF%E0%AE%B4%E0%AF%8D__224_255_255_9\" alt=\"b\" usemap=\"#mymap\">
                <input id=\"cfmdiv\" type=\"text\" style=\"background-color:white;\" readonly>
                <div id=\"buttondiv\" style=\"background-color:grey;\">
                    <button id=\"clearbutton\" class=\"captchabutton\" onclick=\"clearChar()\">CLEAR</button>
                    <button class=\"captchabutton\" name=\"clear\" onclick=\"verifyChar()\">SUBMIT</button>
                </div>
            </div>
        </div>";
    return $parentdiv;
}



function privacystatement(){
 $privstr = "
        <div id=\"privacydiv\" style=\"width:80%;min-width:480px;margin:auto;background-color:white;color:black;\">
        <p style=\"margin:auto;text-align: center;padding-top: 15px;\"><u>THANKS</u></p><br><br>
        <div style=\"width:100%;height:auto;text-align: left; margin-left:10px;\">
            Google Noto Team, Python Pillow and many more communities such as Unicode, Apache,PHP,MDN, Stackoverflow, W3Schools, 
            Netbeans etc..,
        </div><br><br>
        
        <p style=\"margin:auto;text-align: center;padding-top: 15px;\"><u>PRIVACY POLICY</u></p><br><br>
        <div style=\"width:100%;height:auto;text-align: left; margin-left:10px;\">
            Remtcha respects the privacy of the visitors.<br><br> Remtcha collects the following information to improve the web site behaviour
            and to act on any issues reported by the visitor.<br>

            Following information are stored in order to analyze any problems reported by the visitors.<br>
            &nbsp;&nbsp;1. IP Address<br>
            &nbsp;&nbsp;2. Browser type<br>
            &nbsp;&nbsp;3. Screen size of the device used to visit Remtcha.<br><br>

            Remtcha is free to use and is governed by GNU2 license.<br><br>
            By visiting and downloading code from Git , you agree to the licence agreement and accept privacy policy.
        </div><br><br>
        <p style=\"margin:auto;text-align: center;\"><u>OPEN SOURCE</u></p><br><br>
        <div style=\"width:100%;height:auto;text-align: left;margin-left:10px;\">
            Remtcha complies with GPLV3 License. Please refer to gplv3 https://www.gnu.org/licenses/gpl-3.0-standalone.html for detailed information.
        </div><br><br>

        <p style=\"margin:auto;text-align: center;\"><u>ABOUT</u></p><br><br>
        <div style=\"width:100%;height:auto;text-align: left;margin-left:10px;\">
            Remtcha is a visual captcha system, where the user has to click the symbols/characters that they see on the display pad.
            The user is expected to click on the blockpad,matching characters in the sequence provided in the display box.<br><br>

            Users are free to download the code and use it for any purpose that they seem purposeful.
            For any information or to raise an issue either use GIT or our facebook page.<br><br>

            Any updates to Remtcha, will be notified in the Remtcha facebook page. Please like Remtcha facebook page, if you like to receive
            notification on updates.<br>
        </div>
    </div>";
 return $privstr;

}  
?>

<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1, maximum-scale=1,user-scalable=no">
<title>Remcha</title>
<link href="//fonts.googleapis.com/earlyaccess/notosanskannada.css" rel="stylesheet">
<link href="//fonts.googleapis.com/earlyaccess/notosanstelugu.css" rel="stylesheet">
<link href="//fonts.googleapis.com/earlyaccess/notosanstamil.css?family=Noto+Sans+Tamil" rel="stylesheet">
<link href="//fonts.googleapis.com/earlyaccess/notosansmalayalam.css?family=Noto+Sans+Malayalam" rel="stylesheet">
<link href="//fonts.googleapis.com/earlyaccess/notosansbengali.css?family=Noto+Sans+Bengali" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="./index.css"> 
<script>
    /* default options to start with*/
    var menu1="dispbox";
    var menu2="dispfnt";
    var menu3="dispcolor";
    /*disp box default params*/
    var dpfc="22_55_11";
    var dpfco = 5;
    var dpbgc="77_33_11";
    var dpfgco = 6;
    var dpbgo = 4;
    var dpbgi = "variator0";
    var dpfgc = "33_44_11";
    /*blockpad default params*/    
    var bpfc = "55_44_11";
    var bpbgc = "224_255_255";
    var bpbgo = 9;
    var bpbgi = "remb0";
    var bpfco = "9";
    // default foreground image for displaybox
    var dpfgi = "variator_overlay0";
    var rlang="தமிழ்";
    var cfmflag = false;
    
    /*
     * menuopt(val)
     * resets the remaining borders and shows border for the selected one
     * @returns none
     */
    function menuopt(val){
            resetbb();
            resetmenu();
        if(val.id === "dev1"){
            document.getElementById("menu1").style.display = "block";
            val.style.borderBottom = "2px solid white";
        }
        if(val.id === "dev2"){
            document.getElementById("menu2").style.display = "block";
            val.style.borderBottom = "2px solid white";
        }
        if(val.id === "dev3"){
            document.getElementById("menu3").style.display = "block";
            val.style.borderBottom = "2px solid white";
        }        
    }

    /*
     * resetbb
     * resets the borders for items in demo, about , code and stats
     * @returns none
     */
    function resetbb(){
        var ix=1;var temp="";
        for(ix;ix < 4; ix++){
            temp = "dev"+ix;
            document.getElementById(temp).style.borderBottom = "none";
        }
    }

    /*
     * resetmenu
     * resets the dependent menu items
     * @returns none
     */    
    function resetmenu(){
        var ix=1;var temp="";
        for(ix;ix < 4; ix++){
            temp = "menu"+ix;
            document.getElementById(temp).style.display = "none";
        }        
    }

    /*
     * showele
     * elements to shows on body load
     * @returns none
     */
    function showele(){
        document.getElementById("langinfo").style.display = "none";
        var ele = document.getElementById("dev1");
        menuopt(ele);
        ele = document.getElementById("dispbox");
        padop(ele);
        ele = document.getElementById("fntbox");
        styleop(ele);
        ele = document.getElementById("color");
        variantop(ele);
        ajaxreqtorefresh();
        //getmap();
    }

    /*
     * padop
     * elements to shows on body load
     * @returns none
     */
    function padop(val){
        resetpad();
        resetvariantop();
        menu1 = val.id;
        if(menu1 === "blockpad"){
            document.getElementById("fgrnd").style.display = "none";
        }
        else{
            document.getElementById("fgrnd").style.display = "inline";
        }
        val.style.borderBottom = "2px white solid";
        var ele = document.getElementById("fntbox");
        styleop(ele);
        ele = document.getElementById("color");
        variantop(ele);
    }

    /*
     * styleop
     * resets the sub menu for fntbox, background and foreground
     * @returns none
     */
    function styleop(val){
        resetstyleop();
        menu2 = val.id;
        if(menu2 === "fntbox"){
            document.getElementById("img").style.display = "none";
            document.getElementById("color").style.borderBottom = "2px solid white";
        }
        else{
            document.getElementById("opaq").style.display = "inline";
            document.getElementById("img").style.display = "inline";
        }
        val.style.borderBottom = "2px white solid";
        var ele = document.getElementById("color");
        variantop(ele);    
    }

    /*
     * variantop
     * resets the sub menu for color opacity and image
     * @returns none
     */
    function variantop(val){
        resetvariantop();
        val.style.borderBottom = "2px white solid";
        menu3 = val.id;
        // get variant option and send AJAX
        removeimgvariators();
        if(menu3 === "color"){document.getElementById("colorshow").style.display = "block";}
        else if(menu3 === "opaq"){document.getElementById("opaqshow").style.display = "block";}
        else if(menu3 === "img"){
            removeimgvariators();
            if((menu2 === "bgrnd") && (menu1 === "dispbox")){
                // show variator
                document.getElementById("variator").style.display = "block";
            }
            else if((menu2 === "fgrnd") && (menu1 === "dispbox")){
                document.getElementById("variator_overlay").style.display = "block";
            }
            else if((menu2 === "bgrnd") && (menu1 === "blockpad")){
                document.getElementById("blockpad_bg").style.display = "block";
            }
            else if((menu2 === "fgrnd") && (menu1 === "blockpad")){

            }
        }
    }

    /*
     * removeimgvariators
     * removes the irrelevant menu
     * @returns none
     */
    function removeimgvariators(){
        document.getElementById("variator").style.display = "none";
        document.getElementById("variator_overlay").style.display = "none";
        document.getElementById("blockpad_bg").style.display = "none";
    }

    function blockvariants(){
        document.getElementById("colorshow").style.display = "none";
        document.getElementById("opaqshow").style.display = "none";
    }

    function resetvariantop(){
        document.getElementById("color").style.borderBottom = "none";
        document.getElementById("opaq").style.borderBottom = "none";
        document.getElementById("img").style.borderBottom = "none";
        blockvariants();
    }

    function resetpad(){
        document.getElementById("dispbox").style.borderBottom = "none";
        document.getElementById("blockpad").style.borderBottom = "none";
    }



    function resetstyleop(){
        document.getElementById("fntbox").style.borderBottom = "none";
        document.getElementById("bgrnd").style.borderBottom = "none";
        document.getElementById("fgrnd").style.borderBottom = "none";
    }

    function refreshremtcha(){
        //document.getElementById("blockrow").src="dp_php.php?"+formatval();
        document.getElementById("blockrow").src="dp_php.php?"+formatval();
        document.getElementById("block_pad").src="bp_php.php?"+formatval();
        getImageMap();
    }
    
    function printcolor(val){
        var col = val.style.backgroundColor;
        if(menu1 === "dispbox"){
            if(menu2 === "fntbox"){
                dpfc = val.id;
            }
            if(menu2 === "bgrnd"){
                dpbgc = val.id;
            }
            if(menu2 === "fgrnd"){
                dpfgc = val.id;
            }
        }
        if(menu1 === "blockpad"){
            if(menu2 === "fntbox"){
                bpfc = val.id;
            }
            if(menu2 === "bgrnd"){
                bpbgc = val.id;
            }
        }
        ajaxreqtorefresh();
    }


    function printopacity(val){
        
        if((menu2 === "fgrnd") && (menu1 === "dispbox")){
            dpfgo = val.style.opacity * 10;
            //document.getElementById("blockrowoverlay").style.opacity = val.style.opacity;
        }
        else if((menu2 === "bgrnd") && (menu1 === "blockpad")){
            bpbgo = val.style.opacity * 10;
            //document.getElementById("block_pad").style.opacity = val.style.opacity;
        }
        else if((menu2 === "fntbox") && (menu1 === "dispbox")){
            dpfco = val.style.opacity * 10;
        }
        else if((menu2 === "fntbox") && (menu1 === "blockpad")){
            bpfco = val.style.opacity * 10;
        }
        else{
            dpbgo = val.style.opacity * 10;
            //document.getElementById("blockrow").style.opacity = val.style.opacity;
        }
        ajaxreqtorefresh();
    }

    function formatval(){
        var rem = "showrem="+dpfc+"_"+dpfco+"__"+dpbgc+"_"+dpbgo+"__"+dpbgi+"__"+dpfgc+"_"+dpfgco+"__"+ dpfgi+"__"+bpbgc+"_"+bpbgo+"__"+bpbgi+"__"+rlang+"__"+bpfc+"_"+bpfco;
        return rem;
    }
    
    function printimage(val){
        var imgfc = val.src;
        var imgt = imgfc.split("/");
        
        var imgname = imgt[6].split(".");
        imgfc = imgname[0];
        //alert(imgt+"__"+imgfc);
        if((menu2 === "bgrnd") && (menu1 === "dispbox")){
            dpbgi = imgfc;
        }
        if((menu2 === "fgrnd") && (menu1 === "dispbox")){
            dpfgi = imgfc;
        }
        ajaxreqtorefresh();
    }

    function printimagebp(val){
        var imgfc1 = val.src;
        var imgt = imgfc1.split("/");
        var imgname = imgt[5].split(".");
        imgfc1 = imgname[0];
        bpbgi = imgfc1;
        ajaxreqtorefresh();
    }

    function bpentry(val){
        var ele = document.getElementById("cfmdiv");
        ele.value = ele.value + val.textContent;
    }

    function sendcoords(val){
        var tc = document.getElementById("cfmdiv");
        tc.value  = tc.value + val.alt;
    }


    function arrowtrans(val){
        var imgt = (val.src).split("/");
        //alert(imgt);
        if(imgt[5] == "down.png"){
            val.src = "img/remtcha/up.png";
            document.getElementById("langinfo").style.display = "block";
        }
        else{
            val.src = "img/remtcha/down.png";
            document.getElementById("langinfo").style.display = "none";
        }
    }



    function ajaxreqtorefresh(){
        var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var temp = this.responseText;
                    refreshremtcha();
                }
            };
            xmlhttp.open("GET", "remtcha.php?"+formatval(), false);
            xmlhttp.send();    
    }
    
    
    function getImageMap(){
        var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //alert(this.responseText);
                    document.getElementById("tempmap").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "createmap.php?retimgmap=x", false);
            xmlhttp.send();    
    }    

    function getstringfromfile(val){
        rlang = val.textContent;
        ajaxreqtorefresh();
    }
    
    function mapped(val){
        if(cfmflag === true){
            cfmflag = false;
            document.getElementById("cfmdiv").value = "";
        }
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        //document.getElementById("parentdiv").innerHTML = this.responseText;
            var ele = document.getElementById("cfmdiv");
            var valuev = ele.value;
            //alert(this.responseText);
            valuev = valuev + this.responseText;
            ele.value = valuev;
            }
        };
            xmlhttp.open("GET", "genImgMap.php?hashcode="+val.alt, false);
            xmlhttp.send();
    }
        
    function simclickdemo(){
        var ele = document.getElementById("dev2");
        menuopt(ele);
    }
    
    function footopt(val){
    var ele = document.getElementById("privacydiv");
        if(ele.style.display != "block"){
            ele.style.display = "block";
            val.style.borderBottom = "2px solid black";
        }
        else{
            ele.style.display = "none";
            val.style.borderBottom = "0.1px solid black";
        }
    }
    
    function closenotification(){
        document.getElementById("notificationdiv").style.display = "none";
    }
    
    function clearChar(){
        document.getElementById("cfmdiv").value = "";
    }
    
    function verifyChar(){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        //document.getElementById("parentdiv").innerHTML = this.responseText;
            document.getElementById("cfmdiv").value = this.responseText;
            cfmflag = true;
            }
        };
        var val =document.getElementById("cfmdiv").value;
        xmlhttp.open("GET", "verifykeys.php?confirmdiv="+val, false);
        xmlhttp.send();
    }
</script>

</head>
<body onload="showele()">
    <div id="remHeader">
            <b id="remthe" style="font-size:125%;opacity:0.9;float:left;">REMTCHA</b>
            <b  id="dev1" style="margin-left:-2.5%;font-size:115%;margin-right: 2.5%;" onclick="menuopt(this)">ABOUT</b>
            <b  id="dev2" style="font-size:115%;margin-right: 2.5%;" onclick="menuopt(this)">DEMO</b>
            <b  id="dev3" style="font-size:115%;margin-right: 2.5%;" onclick="menuopt(this)">CODE</b>
    </div>
    <div id="notificationdiv" style="width:100%;min-height: 50px;height:auto;background-color:lightyellow;font-size:100%;margin-top: -10px;">
        <b style="float:right;font-size: 30px;" onclick="closenotification()">X</b>
        <p style="min-height: 50px;height:auto;margin-right: 20px;text-align: center;color:black;font-size:120%;">By proceeding further Remtcha web site, you consent to the conditions mentioned in the privacy policy statement,provided at the bottom
        of this web page.</p>
        
    </div>
    <div id="texthint"> </div>
    <!-- menu 1 remtcha  content -->
    <div id="menu1">
                <!-- Add Divs and Images in this section -->
        <div>
            <div id="introdiv" style="width:100%;min-width:480px;height:40%;min-height:300px;"> 
            </div>
            <p style="width:100%;height:240px;opacity:0.5;background-color: whitesmoke;color:black;font-size:125%;margin:auto;text-align: center;border-bottom:1px solid grey;">
                    <br><br><br>
                    Lorem Ipsum Dorum asdfadsf  Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf 

            </p>
            
            <div id="firstdiv" style="width:100%;min-width:480px;height:40%;min-height:300px;margin:auto;font-size:125%;text-align: center;margin-top:10px;"> 
            </div>
            <p style="width:100%;height:240px;opacity:0.5;background-color: whitesmoke;color:black;font-size:125%;margin:auto;text-align: center;border-bottom:1px solid grey;">
                    <br><br><br>Easy to Adapt for Touch and Non-Touch Devices or any screen sizes.
                    <br><br><br>
                    Lorem Ipsum Dorum asdfadsf  Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf 

            </p>

            <div id="seconddiv" style="width:100%;min-width:480px;height:40%;min-height:300px;margin:auto;font-size:125%;text-align: center;margin-top:10px;"> 
            </div>
            <p style="width:100%;height:240px;opacity:0.5;background-color: whitesmoke;color:black;font-size:125%;margin:auto;text-align: center;border-bottom:1px solid grey;">
                    <br><br><br>
                    Lorem Ipsum Dorum asdfadsf  Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf 

            </p>
            <div id="thirddiv" style="width:100%;min-width:480px;height:40%;min-height:300px;margin:auto;font-size:125%;text-align: center;margin-top:10px;"> 
            </div>
            <p style="width:100%;height:240px;opacity:0.5;background-color: whitesmoke;color:black;font-size:125%;margin:auto;text-align: center;border-bottom:1px solid grey;">
                    <br><br><br>
                    Lorem Ipsum Dorum asdfadsf  Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf Lorem Ipsum Dorum asdfadsf 
            </p>
        </div>
    </div>
    <!-- menu 2 demo  content -->        
    <div id="menu2">
        <!-- submenu content -->
        <div id="sym_font">
            <b style="color:white;">SYMBOLS AND FONTS</b><br>
            <img src="img/remtcha/down.png" onclick="arrowtrans(this)"><br>
            <?php echo preparelangdiv(); ?>
        </div>        
        <div id="boxcategory">
            <b id="dispbox" class="ml1" onclick="padop(this)">DISPLAY BOX</b>
            <b id="blockpad" class="ml1" onclick="padop(this)">BLOCK PAD</b>
        </div>            
            <!-- displaybox -->
        <div id="stylecategory"> 
        <!-- Font , background , foreground  -->
            <b id="fntbox" class="ml1" onclick="styleop(this)">FONT</b>
            <b id="bgrnd" class="ml1" onclick="styleop(this)" >BACKGROUND</b>
            <b id="fgrnd" class="ml1" onclick="styleop(this)">FOREGROUND</b>
        </div>
        <div id="variantcategory"> 
        <!-- Font , background , foreground  -->
            <b id="color" class="ml1" onclick="variantop(this)">COLOR</b>
            <b id="opaq" class="ml1" onclick="variantop(this)">OPACITY</b>
            <b id="img" class="ml1" onclick="variantop(this)">IMAGE</b>
        </div>
        <div id="tempmap">
            </div>
            <?php echo showcolordiv(); ?>
            <?php echo opacitydiv(); ?>
            <?php echo variator(); ?>
            <?php echo variator_overlay(); ?>
            <?php echo blockpad_bg(); ?>
            <?php echo showRemtchaDiv();?>
    </div> 

    <!-- menu 3 code  content -->

    <div id="menu3"></div>
    <!-- menu 3 code  content -->
    <div id="footerdiv">
        <div style="margin:auto;text-align: center;padding-top: 10px;padding-bottom: 10px;font-size: 100%;">
        <b id="ft1"  onclick="footopt(this)">PRIVACY ET AL</b><br><br>
        <?php echo privacystatement();?>        
        <b style="font-size:100%;margin:auto;color:white;">Copyright 2017 Manivannan Radhakannan</b>
        </div>
    </div>

    
</body>
</html>