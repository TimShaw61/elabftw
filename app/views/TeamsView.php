<?php
/**
 * \Elabftw\Elabftw\TeamsView
 *
 * @author Nicolas CARPi <nicolas.carpi@curie.fr>
 * @copyright 2012 Nicolas CARPi
 * @see http://www.elabftw.net Official website
 * @license AGPL-3.0
 * @package elabftw
 */
namespace Elabftw\Elabftw;

use \PDO;
use \Elabftw\Elabftw\Teams;

/**
 * HTML for the teams
 */
class TeamsView extends Teams
{
    /** The PDO object */
    protected $pdo;

    /** The Teams class */
    protected $teams;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->teams = new \Elabftw\Elabftw\Teams;
        $this->pdo = $this->teams->pdo;
    }

    /**
     * Output HTML for creating a team
     *
     * @return string $html
     */
    public function showCreate()
    {
        $html = "<div class='box'><h3>" . _('Add a new team') . "</h3>";
        $html .= "<input required type='text' placeholder='Enter new team name' id='teamsName' />";
        $html .= "<button id='teamsCreateButton' onClick='teamsCreate()' class='button'>" . ('Save') . "</button></div>";

        return $html;
    }

    /**
     * Output HTML with all the teams
     *
     * @param array $teamsArr The output of the read() function
     * @return string $html
     */
    public function show()
    {
        $teamsArr = $this->teams->read();

        $html = "<div class='box'><h3>" . _('Edit existing teams') . "</h3>";

        foreach ($teamsArr as $team) {
            $count = $this->getStats($team['team_id']);
            $html .= " <input onKeyPress='teamsUpdateButtonEnable(" . $team['team_id'] . ")' type='text' value='" . $team['team_name'] . "' id='team_" . $team['team_id'] . "' />";
            $html .= " <button disabled id='teamsUpdateButton_" . $team['team_id'] . "' onClick='teamsUpdate(" . $team['team_id'] . ")' class='button'>" . ('Save') . "</button>";
            if ($count['totusers'] == 0) {
                $html .= " <button id='teamsDestroyButton_" . $team['team_id'] . "' onClick='teamsDestroy(" . $team['team_id'] . ")' class='button'>" . ('Delete') . "</button>";
            } else {
                $html .= " <button id='teamsArchiveButton_" . $team['team_id'] . "' onClick='teamsArchive(" . $team['team_id'] . ")' class='button'>" . ('Archive') . "</button>";
            }
            $html .= "<p>" . _('Members') . ": " . $count['totusers'] . " − " . ngettext('Experiment', 'Experiments', $count['totxp']) . ": " . $count['totxp'] . " − " . _('Items') . ": " . $count['totdb'] . " − " . _('Created') . ": " . $team['datetime'] . "<p>";
        }
        $html .= "</div>";
        return $html;
    }

    /**
     * Output HTML with stats
     *
     */
    public function showStats()
    {
        $count = $this->teams->getStats();

        $html = "<div class='box'><h3>" . _('Usage statistics') . "</h3>";
        $html .= "<p>" .
            _('Teams') . ": " . $count['totteams'] . " − " .
            _('Total members') . ": " . $count['totusers'] . " − " .
            ngettext('Total experiment', 'Total experiments', $count['totxp']) . ": " . $count['totxp'] . " − " .
            _('Total items') . ": " . $count['totdb'] . "<p></div>";

        return $html;
    }
}
