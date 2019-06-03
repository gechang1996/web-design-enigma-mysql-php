<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/15/2018
 * Time: 6:54 PM
 */

namespace Enigma;


class User
{
    /**
     * Constructor
     * @param $row Row from the user table in the database
     */
    public function __construct($row) {
        $this->id = $row['id'];
        $this->date=$row['date'];
        $this->email = $row['email'];
        $this->name = $row['name'];

    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    public function setEmail($email)
    {
        $this->email = $email;
    }


    public function setName($name)
    {
        $this->name = $name;
    }



    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
    const SESSION_NAME = 'user';




    private $id;		///< The internal ID for the user
    private $email;		///< Email address
    private $name; 		///< Name as last, first
    private $date;


}