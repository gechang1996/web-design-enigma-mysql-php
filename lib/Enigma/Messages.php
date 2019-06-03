<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/17/2018
 * Time: 12:22 AM
 */

namespace Enigma;


class Messages extends Table
{
    public function __construct(Site $site) {
        parent::__construct($site, "message");
    }
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
        return new Message($statement->fetch(\PDO::FETCH_ASSOC));
    }

    public function sendMessage(message $message){
        $sql=<<<SQL
INSERT INTO $this->tableName(sender,receiver,content,date,code)
VALUE(?,?,?,?,?)
SQL;
        $pdo=$this->pdo();
        $statement=$pdo->prepare($sql);
        $statement->execute(array($message->getSender(),$message->getReceiver(),$message->getContent(),$message->getDate(),$message->getCode()));

        return $pdo->lastInsertId();

    }
    public function receiveMessage($receiver){
        $sql=<<<SQL
SELECT * FROM $this->tableName
WHERE receiver=?
SQL;

        $pdo=$this->pdo();
        $statement=$pdo->prepare($sql);
        $statement->execute(array($receiver));
        if($statement->rowCount()===0){
            return [];
        }
        else{
            $a=$statement->fetchAll(\PDO::FETCH_ASSOC);
            $return_ary=array();
            foreach ($a as $item){
                $return_ary[]=new Message($item);
            }

            return $return_ary;
        }
    }
}