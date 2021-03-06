<?php
/**
 * \Elabftw\Elabftw\Status
 *
 * @author Nicolas CARPi <nicolas.carpi@curie.fr>
 * @copyright 2012 Nicolas CARPi
 * @see https://www.elabftw.net Official website
 * @license AGPL-3.0
 * @package elabftw
 */
namespace Elabftw\Elabftw;

use PDO;
use Exception;

/**
 * Things related to status in admin panel
 */
class Status extends Entity
{
    /**
     * Constructor
     *
     * @param int $team
     */
    public function __construct($team)
    {
        $this->team = $team;
        $this->pdo = Db::getConnection();
    }

    /**
     * Create a new status
     *
     * @param string $name
     * @param string $color
     * @return int id of the new item
     */
    public function create($name, $color)
    {
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        // we remove the # of the hexacode and sanitize string
        $color = filter_var(substr($color, 0, 6), FILTER_SANITIZE_STRING);

        if (strlen($name) < 1) {
            $name = 'Unnamed';
        }

        $sql = "INSERT INTO status(name, color, team, is_default) VALUES(:name, :color, :team, :is_default)";
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':name', $name);
        $req->bindParam(':color', $color);
        $req->bindParam(':team', $this->team);
        $req->bindValue(':is_default', 0);

        $req->execute();

        return $this->pdo->lastInsertId();
    }

    /**
     * SQL to get all status from team
     *
     * @return array All status from the team
     */
    public function readAll()
    {
        $sql = "SELECT * FROM status WHERE team = :team ORDER BY ordering ASC";
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':team', $this->team);
        $req->execute();

        return $req->fetchAll();
    }

    /**
     * Get the color of a status
     *
     * @param int $status ID of the status
     * @return string
     */
    public function readColor($status)
    {
        $sql = "SELECT color FROM status WHERE id = :id";
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':id', $status, PDO::PARAM_INT);
        $req->execute();

        return $req->fetchColumn();
    }

    /**
     * Remove all the default status for a team.
     * If we set true to is_default somewhere, it's best to remove all other default
     * in the team so we won't have two default status
     *
     * @return bool true if sql success
     */
    private function setDefaultFalse()
    {
        $sql = "UPDATE status SET is_default = 0 WHERE team = :team";
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':team', $this->team);

        return $req->execute();
    }

    /**
     * Update a status
     *
     * @param int $id ID of the status
     * @param string $name New name
     * @param string $color New color
     * @param bool $isDefault
     * @return bool true if sql success
     */
    public function update($id, $name, $color, $isDefault)
    {
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $color = filter_var($color, FILTER_SANITIZE_STRING);

        if (($isDefault != 'false') && $this->setDefaultFalse()) {
            $default = 1;
        } else {
            $default = 0;
        }

        $sql = "UPDATE status SET
            name = :name,
            color = :color,
            is_default = :is_default
            WHERE id = :id AND team = :team";

        $req = $this->pdo->prepare($sql);
        $req->bindParam(':name', $name);
        $req->bindParam(':color', $color);
        $req->bindParam(':is_default', $default);
        $req->bindParam(':id', $id);
        $req->bindParam(':team', $this->team);

        return $req->execute();
    }

    /**
     * Count all experiments with this status
     *
     * @param int $id
     * @return int
     */
    private function countExperiments($id)
    {
        $sql = "SELECT COUNT(*) FROM experiments WHERE status = :status";
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':status', $id, PDO::PARAM_INT);
        $req->execute();

        return (int) $req->fetchColumn();
    }

    /**
     * Destroy a status
     *
     * @param int $id id of the status
     * @return bool
     */
    public function destroy($id)
    {
        // don't allow deletion of a status with experiments
        if ($this->countExperiments($id) > 0) {
            throw new Exception(_("Remove all experiments with this status before deleting this status."));
        }

        $sql = "DELETE FROM status WHERE id = :id";
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':id', $id);

        return $req->execute();
    }
}
