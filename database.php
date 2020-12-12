<?php

$dsn = "mysql:dbname=kaw37;host=sql1.njit.edu;port=3306"; //"mysql:dbname=devTest;host=localhost;port=3306";
$usr = "kaw37";
$pwd = "Kawaiiasfuck0315__";

function getConn() {
    global $dsn, $usr, $pwd;
    return new PDO($dsn, $usr, $pwd);
}

function login($email, $password) {
    try {
        $dbh = getConn();
        $sql = "SELECT id, fname, lname FROM accounts WHERE email = ? AND password = ?";
        echo "login: $email / $password";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);

        $stmt->bindParam(2, $password, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            echo "not found";
            return 0;
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $row['id'];
        $_SESSION['UserID'] = $row['id'];
        $_SESSION['UserEmail'] = $email;
        $_SESSION['UserFullName'] = $row['lname'] . ", " . $row['fname'];
        //echo "returning $id";
        $dbh = null;
        return $id;
    } catch (PDOException $ex) {
        echo $ex;
        return 0;
    }
}

function currentUser() {
    if (!isset($_SESSION['UserID'])) {
        return null;
    }
    $sql = "SELECT * FROM accounts WHERE id = ?";
    $dbh = getConn();
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(1, $_SESSION['UserID'], PDO::PARAM_INT);

    $stmt->execute();
    if ($stmt->rowCount() < 1) {
        return null;
    }
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function register($firstName, $lastName, $email, $birthday, $password) {
    try {
        $dbh = getConn();
        $sql = "INSERT INTO `accounts` ( `email`, `fname`, `lname`, `birthday`, `password`) VALUES (?,?,?,?,?) ";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $firstName, PDO::PARAM_STR);
        $stmt->bindParam(3, $lastName, PDO::PARAM_STR);
        $stmt->bindParam(4, $birthday, PDO::PARAM_STR);
        $stmt->bindParam(5, $password, PDO::PARAM_STR);
        $stmt->execute();
        $dbh = null;
        return 1;
    } catch (PDOException $ex) {
        echo $ex;
        return 0;
    }
}

function addQuestion($email, $userId, $questionName, $questionBody, $questionSkills) {

    $sql = "INSERT INTO `questions` (`owneremail`, `ownerid`, `createddate`, `title`, `body`, `skills`, `score`)        
                VALUES (?,?,SYSDATE(),?,?,?,0) ";

    echo "question: $questionName";
    try {
        $dbh = getConn();
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $userId, PDO::PARAM_INT);
        $stmt->bindParam(3, $questionName, PDO::PARAM_STR);
        $stmt->bindParam(4, $questionBody, PDO::PARAM_STR);
        $stmt->bindParam(5, $questionSkills, PDO::PARAM_STR);
        $stmt->execute();

        if (!$stmt) {
            $dbh = null;
            return 0;
        }
        $dbh = null;
        return 1;
    } catch (PDOException $ex) {
        $dbh = null;
        echo $ex;
        return 0;
    }
}

function saveQuestion($id, $questionName, $questionBody, $questionSkills) {
    $sql = "UPDATE questions SET title=?, body=?, skills=? WHERE id = ?";

    //echo "question: $questionName";
    try {
        $dbh = getConn();
        $stmt = $dbh->prepare($sql);

        $stmt->bindParam(1, $questionName, PDO::PARAM_STR);
        $stmt->bindParam(2, $questionBody, PDO::PARAM_STR);
        $stmt->bindParam(3, $questionSkills, PDO::PARAM_STR);
        $stmt->bindParam(4, $id, PDO::PARAM_INT);
        $stmt->execute();

        if (!$stmt) {
            $dbh = null;
            return 0;
        }
        $dbh = null;
        return 1;
    } catch (PDOException $ex) {
        $dbh = null;
        echo $ex;
        return 0;
    }
}

function getQuestion($id) {
    try {
        $dbh = getConn();
        $sql = "SELECT * FROM questions WHERE id = ?";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        if (!$stmt) {
            $dbh = null;
            return 0;
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $dbh = null;
        return $row;
    } catch (PDOException $ex) {
        $dbh = null;
        echo $ex;
        return 0;
    }
}

function getQuestions() {
    
}

function delQuestion($id) {
    $sql = "DELETE FROM questions WHERE id = ?";

    try {
        $dbh = getConn();
        $stmt = $dbh->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        if (!$stmt) {
            $dbh = null;
            return 0;
        }
        $dbh = null;
        return 1;
    } catch (PDOException $ex) {
        $dbh = null;
        echo $ex;
        return 0;
    }
}

function question_Save() {
    
}

function checkDateFormat($date) {
    // match the format of the date
    if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {

        // check whether the date is valid or not
        if (checkdate($parts[2], $parts[3], $parts[1])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

?>