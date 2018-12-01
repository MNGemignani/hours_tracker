<?php
require_once 'Database.php';

class User
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

    public function lasdID()
    {
        $stmt = $this->conn->lastInsertId();
        return $stmt;
    }

    public function add_hour($date,$start_hours, $end_hours, $pause, $note)
    {
        try
        {
            $stmt = $this->conn->prepare(
                "INSERT INTO hours(date,start_hours,end_hours,pause,note) 
			                    VALUES(:date, :start_hours, :end_hours, :pause, :note)"
            );
            $stmt->bindparam(":date",$date);
            $stmt->bindparam(":start_hours",$start_hours);
            $stmt->bindparam(":end_hours",$end_hours);
            $stmt->bindparam(":pause",$pause);
            $stmt->bindparam(":note",$note);

            $stmt->execute();
            return $stmt;
        }
        catch(PDOException $ex)
        {
            echo $ex->getMessage();
        }
    }

    public function edit_hour($date, $start_hours, $end_hours, $pause, $note, $id)
    {
        try
        {
            $stmt_edit = $this->conn->prepare(
                    "UPDATE `hours` 
                            SET `date` = :date, 
                            `start_hours` = :start_hours, 
                            `end_hours` = :end_hours, 
                            `pause` = :pause, 
                            `note` = :note
                            WHERE `id` = :uid"
            );
            $stmt_edit->bindparam(":date",$date,PDO::PARAM_STR);
            $stmt_edit->bindparam(":start_hours",$start_hours,PDO::PARAM_STR);
            $stmt_edit->bindparam(":end_hours",$end_hours,PDO::PARAM_STR);
            $stmt_edit->bindparam(":pause",$pause,PDO::PARAM_STR);
            $stmt_edit->bindparam(":note",$note,PDO::PARAM_STR);
            $stmt_edit->bindparam(":uid",$id,PDO::PARAM_STR);

            $stmt_edit->execute();
            return $stmt_edit;
        }
        catch(PDOException $ex)
        {
            echo $ex->getMessage();
        }
    }

    public function convertHours ($hours, $minutes) {
        return $hours + round($minutes / 60, 2);
    }
}