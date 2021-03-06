<?php
/**
 * make.php
 *
 * @author Nicolas CARPi <nicolas.carpi@curie.fr>
 * @copyright 2012 Nicolas CARPi
 * @see https://www.elabftw.net Official website
 * @license AGPL-3.0
 * @package elabftw
 */
namespace Elabftw\Elabftw;

use \Exception;

/**
 * Create a csv, zip or pdf file
 *
 */

require_once 'app/init.inc.php';
$page_title = _('Export');
$selected_menu = null;

try {
    switch ($_GET['what']) {
        case 'csv':
            $make = new MakeCsv($_GET['id'], $_GET['type']);
            break;

        case 'zip':
            $make = new MakeZip($_GET['id'], $_GET['type']);
            break;

        case 'pdf':
            $make = new MakePdf($_GET['id'], $_GET['type']);
            break;

        default:
            throw new Exception(_('Bad type!'));
    }

    // the pdf is shown directly, but for csv or zip we want a download page
    if ($_GET['what'] === 'csv' || $_GET['what'] === 'zip') {
        require_once 'app/head.inc.php';

        echo "<div class='well' style='margin-top:20px'>";
        echo "<p>" . _('Your file is ready:') . "<br>
                <a href='app/download.php?type=" . $_GET['what'] . "&f=" . $make->fileName . "&name=" . $make->getCleanName() . "' target='_blank'>
                <img src='app/img/download.png' alt='download' /> " . $make->getCleanName() . "</a>
                <span class='filesize'>(" . Tools::formatBytes(filesize($make->filePath)) . ")</span></p>";
        echo "</div>";
    }

} catch (Exception $e) {
    require_once 'app/head.inc.php';
    display_message('ko', $e->getMessage());

} finally {
    // this won't show up if it's a pdf
    require_once 'app/footer.inc.php';
}
