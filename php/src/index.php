<?php

ob_start(); #needed for redirect to work
session_start();
error_reporting(E_ERROR | E_PARSE); #hides error and warning messages

include __DIR__ . '/Helper/DotEnv.php';
(new DotEnv(__DIR__ . '/.env'))->load();

$host = 'db';
$user = 'root';
$pass = getenv('MYSQL_ROOT_PASSWORD');

ob_flush();
// check the MySQL connection status
$conn = new mysqli($host, $user, $pass, 'cp476');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
// else {
//     print("Connected to MySQL server successfully!\n");
// }

// NAME TABLE:

$sql = "SELECT StudentID from Name_Table";
$result = mysqli_query($conn, $sql);

if (empty($result)) {
    $sql = "CREATE TABLE Name_Table (
        StudentID int,
        StudentName varchar(255)
    )";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}

// Check Name Table for records:
$sql = "SELECT * FROM Name_Table";
// Execute query:
$result = mysqli_query($conn, $sql);

// If Name Table does not contain records:
if (mysqli_num_rows($result) < 25) {
    $namefile = fopen('NameFile.txt','r');

    while (!feof($namefile)) {
        $getTextLine = fgets($namefile);
        $explodeLine = explode(", ",$getTextLine);
        
        list($studentid, $name) = $explodeLine;
        
        $query = "INSERT INTO Name_Table (StudentID, StudentName) values('".$studentid."','".$name."')";
        mysqli_query($conn,$query);
    }
    
    fclose($namefile);
}

// COURSE TABLE:

$sql = "SELECT StudentID from Course_Table";
$result = mysqli_query($conn, $sql);

if (empty($result)) {
    $sql = "CREATE TABLE Course_Table (
        StudentID int,
        CourseCode varchar(255),
        Test1 int,
        Test2 int,
        Test3 int,
        Final int
    )";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}

// Check Course Table for records:
$sql = "SELECT * FROM Course_Table";
// Execute query:
$result = mysqli_query($conn, $sql);

$rowcount = mysqli_num_rows($result);

// If Course Table does not contain records:
if ($rowcount < 50) {
    $namefile = fopen('CourseFile.txt','r');

    while (!feof($namefile)) {
        $getTextLine = fgets($namefile);
        $explodeLine = explode(", ",$getTextLine);
        
        list($studentid, $course, $test1, $test2, $test3, $final) = $explodeLine;
        
        $query = "INSERT INTO Course_Table (StudentID, CourseCode, Test1, Test2, Test3, Final) values('".$studentid."','".$course."','".$test1."','".$test2."','".$test3."','".$final."')";
        mysqli_query($conn,$query);
    }
    
    fclose($namefile);
}

include("login.html");


#find student ID in name table
#prepared statement
$stmt = $conn->prepare("SELECT * FROM Name_Table WHERE StudentID = ?");
$stmt->bind_param("i", $id);
$id = filter_var($_POST['id'], FILTER_VALIDATE_INT) !== false ? $_POST['id'] : ""; #validate id input
$stmt->execute();
$result = $stmt->get_result();
$student_name = mysqli_fetch_array($result)[1]; #obtain student name from result

#set session variables
$_SESSION['studentname'] = $student_name;
$_SESSION['id'] = $id;

#if student ID is found from name table then get rows from course table containing student id
if(mysqli_num_rows($result) > 0){
    
    #redirect to main.html
    header("Location: /main.php");
    //exit;
    $stmt = $conn->prepare("SELECT CourseCode, Test1, Test2, Test3, Final FROM Course_Table WHERE StudentID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    #get each line from query and push to rows array
    $rows = [];
    while($row = mysqli_fetch_array($result)){
        array_push($rows, $row);
    }
    $_SESSION['results'] = $rows; #store rows in post variable

}else{
    // if (isset($id) & isset($stmt)){
    //     echo '<script>alert("Invalid Student Id.")</script>';
    // }
}
    
#else student ID does not exist in name table


#update student grade

