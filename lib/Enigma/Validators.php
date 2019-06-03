<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/16/2018
 * Time: 1:10 AM
 */

namespace Enigma;


class Validators extends Table
{
    public function __construct(Site $site) {
        parent::__construct($site, "validator");
    }


    /**
     * Generate a random validator string of characters
     * @param $len Length to generate, default is 32
     * @returns Validator string
     */
    public function createValidator($len = 32) {
        $bytes = openssl_random_pseudo_bytes($len / 2);
        return bin2hex($bytes);
    }
    /**
     * Create a new validator and add it to the table.
     * @param $userid User this validator is for.
     * @return The new validator.
     */
    public function newValidator($userid) {
        $validator = $this->createValidator();

        // Write to the table
        $sql = <<<SQL
INSERT INTO $this->tableName(validator, userid, date)
values(?, ?, ?)
SQL;

        $statement = $this->pdo()->prepare($sql);

        $statement->execute(array($validator,$userid,date("Y-m-d")));

        return $validator;
    }
    /**
     * Determine if a validator is valid. If it is,
     * return the user ID for that validator.
     * @param $validator Validator to look up
     * @return User ID or null if not found.
     */
    public function get($validator) {
        $sql =<<<SQL
SELECT userid from $this->tableName
where validator=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($validator));
        if($statement->rowCount() === 0) {
            return null;
        }
        $result=$statement->fetch(\PDO::FETCH_ASSOC);
        return $result['userid'];
    }

    /**
     * Remove any validators for this user ID.
     * @param $userid The USER ID we are clearing validators for.
     */
    public function remove($userid) {
        $sql=<<<SQL
DELETE FROM $this->tableName
WHERE userid=?
SQL;
        $pdo = $this->pdo();
        $statement1 = $pdo->prepare($sql);
        $statement1->execute(array($userid));

    }
}