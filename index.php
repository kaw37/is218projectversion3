<?php

session_start();

require("database.php");
$method = 1;
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $method = 2;
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        //$action = 'list_products';
        if (isset($_SESSION['UserID'])) {
            include("project1index.php");
        } else {
            include("login.html");
        }
    }
}

if ($action == "login") {
    $email = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password');
    if ($email && $password) {
        $id = login($email, $password);
        if ($id == 0) {
            die("login failed");
        }
        echo "logged in successfully";
        include("project1index.php");
    } else {
        include("login.html");
    }
} else if ($action == "register") {
    if ($method == 2) {
        if (isset($_SESSION['UserID'])) {
            $row = currentUser();
            extract ($row);
            include("registration.php");
            return;
        }else{
            include("registration.html");
            return;
        }
    }

    extract($_POST);
    //echo "$firstName, $lastName, $email, $birthday, $password";
    if (!$firstName || !$lastName || !$email || !$birthday || !$password) {
        //echo "missing data";
        include("registration.html");
    } else {
        $id = register($firstName, $lastName, $email, $birthday, $password);
        if ($id == 0) {
            die("registration failed");
        }
        //echo "logged in successfully";
        include("project1index.php");
    }
} else if ($action == "addQuestion") {
    $email = "";
    $userId = 0;
    if (isset($_SESSION['UserID'])) {
        $email = $_SESSION['UserEmail'];
        $userId = $_SESSION['UserID'];
    } else {
        die("user not logged in");
    }
    if ($method == 2) { // get method.
        include("questions.html");
    }
    extract($_POST);
    if (!$questionName || !$questionSkills || !$questionBody) {
        die("no question data");
    }
    if (!isset($questionId))
        $id = addQuestion($email, $userId, $questionName, $questionBody, $questionSkills);
    else
        $id = saveQuestion($questionId, $questionName, $questionBody, $questionSkills);
    if ($id == 1)
        include("project1index.php");
    else {
        echo "question not saved";
    }
} else if ($action == "question") {
    if ($method == 2) {
        $id = filter_input(INPUT_GET, 'id');
        if ($id > 0) {
            $row = getQuestion($id);
            extract($row);
            include ("question.php");
        } else
            die("no id");
    } else {
        die("post question");
    }
} else if ($action == "delQuestion") {
    $id = filter_input(INPUT_GET, 'id');
    if ($id > 0) {
        $row = delQuestion($id);
       include("project1index.php");
    } else
        die("no id for question to delete");
}
?>
