<?php
/* 
 * 
 *   session_handler : track and manage sessions
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
 *   and open the template in the editor.
 * 
 *   part of the code was taken from php.net
 */
function my_session_start() {
    session_start();
    // Do not allow to use too old session ID
    if (!empty($_SESSION['deleted_time']) && $_SESSION['deleted_time'] < time() - 180) {
        session_destroy();
        session_start();
    }
}

function getmicrotimestamp(){
    $now = new DateTime();
    $cutime = $now->format('Y-m-d-H-i-s');
    $microsecarr = preg_split("[\s]",microtime());
    $currentts = $microsecarr[0] * 1000000;
    return $cutime."-".$currentts;

}

// My session regenerate id function
function my_session_regenerate_id() {
    // Call session_create_id() while session is active to 
    // make sure collision free.
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    // WARNING: Never use confidential strings for prefix!
    $newid = sha1("Hello Mani").  getmicrotimestamp();
    //$newid = session_create_id('myprefix-'.$randid);
    // Set deleted timestamp. Session data must not be deleted immediately for reasons.
    $_SESSION['deleted_time'] = time();
    // Finish session
    session_commit();
    // Make sure to accept user defined session ID
    // NOTE: You must enable use_strict_mode for normal operations.
    ini_set('session.use_strict_mode', 0);
    // Set new custome session ID
    session_id($newid);
    // Start with custome session ID
    session_start();
}

?>
