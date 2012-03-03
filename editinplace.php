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
require_once('inc/connect.php');
// TODO check comment is owned by user

if (isset($_POST['id']) && !empty($_POST['id'])) {
// post['id'] looks like comment_56
$id_arr = explode('_', $_POST['id']);
if (filter_var($id_arr[1], FILTER_VALIDATE_INT)) {
    $id = $id_arr[1];
}else{
    die('diephrase');
}
}
// TODO if user send multiple space, comment field disappear !
if (($_POST['content'] != '') && ($_POST['content'] != ' ')){
    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
    // SQL to update single file comment
    $sql = "UPDATE uploads SET comment = :new_comment WHERE id = :id";
    $req = $bdd->prepare($sql);
    $req->execute(array(
        'new_comment' => $content,
        'id' => $id));
    echo stripslashes($content);
} else { // Submitted comment is empty
    // Get old comment
    $sql = "SELECT comment FROM uploads WHERE id = ".$id;
    $req = $bdd->prepare($sql);
    $req->execute();
    $data = $req->fetch();
    echo stripslashes($data['comment']);
}
?>
