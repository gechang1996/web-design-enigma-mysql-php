<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/15/2018
 * Time: 6:48 PM
 */

namespace Enigma;


class Users extends Table
{
    public function __construct(Site $site) {
        parent::__construct($site, "user");

    }


    /**
     * Get a user based on the id
     * @param $id ID of the user
     * @returns User object if successful, null otherwise.
     */
    public function get($id) {
        $sql =<<<SQL
SELECT * from $this->tableName
where id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($id));
        if($statement->rowCount() === 0) {
            return null;
        }

        return new User($statement->fetch(\PDO::FETCH_ASSOC));
    }

    /**
     * Modify a user record based on the contents of a User object
     * @param User $user User object for object with modified data
     * @return true if successful, false if failed or user does not exist
     */
//    public function update(User $user) {
//
//        $id=$user->getId();
//        $sql =<<<SQL
//SELECT * from $this->tableName
//where id=?
//SQL;
//
//        $pdo = $this->pdo();
//        $statement = $pdo->prepare($sql);
//        $statement->execute(array($id));
//        if($statement->rowCount() === 0) {
//            return false;
//        }
//        $id=$user->getId();
//        $email=$user->getEmail();
//        $sql =<<<SQL
//SELECT * from $this->tableName
//where id<>? and email=?
//SQL;
//        $pdo = $this->pdo();
//        $statement = $pdo->prepare($sql);
//        $statement->execute(array($id,$email));
//        if($statement->rowCount() !== 0) {
//            return false;
//        }
//
//        $email=$user->getEmail();
//        $name=$user->getName();
//        $phone=$user->getPhone();
//        $address=$user->getAddress();
//        $note=$user->getNotes();
//        $role=$user->getRole();
//        $id=$user->getId();
//        $joined=$user->getJoined();
//        $sql2=<<<SQL
//update $this->tableName
//set name=? , phone=?, address=?, notes=?, role=?,joined=?,email=?
//where id=?
//SQL;
//        $pdo = $this->pdo();
//        $statement1 = $pdo->prepare($sql2);
//        $statement1->execute(array($name,$phone,$address,$note,$role,$joined,$email,$id));
//
//        return true;
//

//
//    }






    /**
     * Test for a valid login.
     * @param $email User email
     * @param $password Password credential
     * @returns User object if successful, null otherwise.
     */
    public function login($email, $password) {

        $sql =<<<SQL
SELECT * from $this->tableName
where email=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($email));
        if($statement->rowCount() === 0) {
            return null;
        }

        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        // Get the encrypted password and salt from the record
        $hash = $row['password'];
        $salt = $row['salt'];

        // Ensure it is correct
        if($hash !== hash("sha256", $password . $salt)) {
            return null;
        }
        return new User($row);

    }
    /**
     * Create a new user.
     * @param User $user The new user data
     * @param Email $mailer An Email object to use
     * @return null on success or error message if failure
     */
    public function add(User $user, Email $mailer) {
        // Ensure we have no duplicate email address
        if($this->exists($user->getEmail())) {
            return "Email address already exists.";
        }
        // Add a record to the user table
        $sql = <<<SQL
INSERT INTO $this->tableName(email, name,date)
values(?, ?,?)
SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute(array(
            $user->getEmail(), $user->getName(),$user->getDate()));
        $id = $this->pdo()->lastInsertId();
        // Create a validator and add to the validator table
        $validators = new Validators($this->site);
        $validator = $validators->newValidator($id);
        // Send email with the validator in it
        $link = "http://webdev.cse.msu.edu"  . $this->site->getRoot() .
            '/password-validate.php?v=' . $validator;

        $from = $this->site->getEmail();
        $name = $user->getName();

        $subject = "Confirm your email";
        $message = <<<MSG
<html>
<p>Greetings, $name,</p>

<p>Welcome to the Endless Enigma. In order to complete your registration,
please verify your email address by visiting the following link:</p>

<p><a href="$link">$link</a></p>
</html>
MSG;
        $headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=iso=8859-1\r\nFrom: $from\r\n";
        $mailer->mail($user->getEmail(), $subject, $message, $headers);
        return "";
    }
    /**
     * Determine if a user exists in the system.
     * @param $email An email address.
     * @returns true if $email is an existing email address
     */
    public function exists($email) {
        $sql =<<<SQL
SELECT * from $this->tableName
where email=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($email));
        if($statement->rowCount() === 0) {
            return false;
        }
        return true;

    }

    /**
     * Set the password for a user
     * @param $userid The ID for the user
     * @param $password New password to set
     */
    public function setPassword($userid, $password) {
        $sql =<<<SQL
update $this->tableName
set password=? , salt=?
where id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $salt=self::randomSalt();

        $hash=hash("sha256", $password . $salt);
        $statement->execute(array($hash,$salt,$userid));


    }
    /**
     * Generate a random salt string of characters for password salting
     * @param $len Length to generate, default is 16
     * @returns Salt string
     */
    public static function randomSalt($len = 16) {
        $bytes = openssl_random_pseudo_bytes($len / 2);
        return bin2hex($bytes);
    }
    public function getUsers(){
        $sql=<<<SQL
SELECT * FROM $this->tableName


SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute();
        $a=$statement->fetchAll(\PDO::FETCH_ASSOC);
        $return_ary=array();
        foreach ($a as $item){
            $return_ary[]=new User($item);
        }

        return $return_ary;
    }
//    public function getAllNames(){
//        $sql=<<<SQL
//SELECT name FROM $this->tableName
//
//
//SQL;
//        $pdo = $this->pdo();
//        $statement = $pdo->prepare($sql);
//
//        $statement->execute();
//        return $statement->fetchAll(\PDO::FETCH_ASSOC);
//
//    }


}