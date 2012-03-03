<?php
/********************************************************************************
*                                                                               *
*   Copyright 2012 Nicolas CARPi (nicolas.carpi@gmail.com)                      *
*   http://www.elabftw.net/                                                     *
*                                                                               *
********************************************************************************/

/********************************************************************************
*  This file is part of eLabFTW.                                                *
*                                                                               *
*    eLabFTW is free software: you can redistribute it and/or modify            *
*    it under the terms of the GNU Affero General Public License as             *
*    published by the Free Software Foundation, either version 3 of             *
*    the License, or (at your option) any later version.                        *
*                                                                               *
*    eLabFTW is distributed in the hope that it will be useful,                 *
*    but WITHOUT ANY WARRANTY; without even the implied                         *
*    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR                    *
*    PURPOSE.  See the GNU Affero General Public License for more details.      *
*                                                                               *
*    You should have received a copy of the GNU Affero General Public           *
*    License along with eLabFTW.  If not, see <http://www.gnu.org/licenses/>.   *
*                                                                               *
********************************************************************************/
require_once('inc/auth.php');
$page_title = 'Profile';
require_once('inc/head.php');
require_once('inc/menu.php');
require_once('inc/connect.php');
require_once('inc/functions.php');
echo '<h2>PROFILE</h2>';

// SQL to get number of experiments
$sql = "SELECT COUNT(*) FROM experiments WHERE userid = ".$_SESSION['userid'];
$req = $bdd->prepare($sql);
$req->execute();

$count = $req->fetch();

// SQL for profile
$sql = "SELECT * FROM users WHERE userid = ".$_SESSION['userid'];
$req = $bdd->prepare($sql);
$req->execute();
$data = $req->fetch();
$days_since_reg = daydiff($data['register_date']);
// if user registered today
if (daydiff(date("Y-m-d")) === 0){
    $days_since_reg = 1;
}
$exp_per_day = floor($count[0] / $days_since_reg);

echo "<section class='item'>";
echo "<img src='img/user.png' alt='' /> <h4>INFOS</h4>";
echo "<div class='center'>
    <p>".$data['firstname']." ".$data['lastname']." (".$data['email'].")</p>
    <p>".$count[0]." experiments done since ".$data['register_date']." (".$exp_per_day." experiments/day)</p>";
if($data['group'] == 'admin') {echo "<p>You ARE admin \o/</p>";}
if($data['group'] === 'journalclub') {echo "<p>You ARE responsible of the <a href='journal-club.php'>Journal Club</a> !</p>";}
echo "</div>";

echo "<hr>";
require_once('inc/statistics.php');
echo "<hr>";
require_once('inc/tagcloud.php');

echo "</section>";

require_once('inc/footer.php');
?>
