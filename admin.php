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
/* admin.php - for administration of the elab */
require_once('inc/common.php');
if ($_SESSION['is_admin'] != 1) {die('You are not admin !');}
$title = 'Admin Panel';
require_once('inc/head.php');
require_once('inc/menu.php');
require_once('inc/info_box.php');
?>
<h2>ADMIN PANEL</h2>
<?php
// SQL to get all unvalidated users
$sql = "SELECT userid, lastname, firstname, email FROM users WHERE validated = 0";
$req = $bdd->prepare($sql);
$req->execute();
$count = $req->rowCount();
// only show the frame if there is some users to validate
if ($count > 0) {
    echo "
<section class='fail'>
<h3>USERS WAITING FOR VALIDATION</h3>";
echo "<form method='post' action='admin-exec.php'><ul>";
while ($data = $req->fetch()) {
    echo "<li><input type='checkbox' name='validate[]' value='".$data['userid']."'> ".$data['firstname']." ".$data['lastname']." (".$data['email'].")</li>";
}
echo "</ul><input type='submit' name='submit' value='Validate users' /></form>";
echo "</section>";
}
?>

<section class='item'>
<h3>TEAM MEMBERS</h3>
<?php
// TODO different colors for different groups
// SQL to get all users
$sql = "SELECT userid, lastname, firstname, email FROM users WHERE validated = 1";
$req = $bdd->prepare($sql);
$req->execute();
echo "<form method='post' action='admin-exec.php'><ul>";
while ($data = $req->fetch()) {
    echo "<li>".$data['firstname']." ".$data['lastname']." (".$data['email'].") :: <a href='admin-exec.php?deluser=".$data['userid']."'>delete</a> <a href='admin-exec.php?edituser=".$data['userid']."'>edit</a></li>";
}
echo "</section>";
require_once('inc/footer.php') ?>
