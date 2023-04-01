<?php

include __DIR__ . '/Helper/DotEnv.php';
(new DotEnv(__DIR__ . '/.env'))->load();

$host = 'db';
$user = 'root';
$pass = getenv('MYSQL_ROOT_PASSWORD');

// check the MySQL connection status
$conn = new mysqli($host, $user, $pass, 'cp476');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    print("Connected to MySQL server successfully!\n");
}


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