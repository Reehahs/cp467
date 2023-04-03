<?php
session_start();
?>
<!DOCTYPE html>
<html>

<style>
body {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 50%;
}

table {
  border: 1px solid #ddd;
  padding: 8px;
}

table tr:nth-child(even){background-color: #f2f2f2;}

table tr:hover {background-color: #ddd;}

table th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
    
table, th, td {
  border:1px solid black;
}
tfoot {
        font-weight: bold;
      }
      .logout-button {
        float: right;
        margin-top: 20px;
        margin-right: 20px;
        padding: 10px;
        background-color: #f44336;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
      }
      .logout-button:hover {
        background-color: #d32f2f;
      }
</style>
<body>
<form action="index.php" method="post">
  <button class="logout-button" type="submit">Logout</button>   
</form> 
<center>
<h2>Student Home</h2>
</center>

<form>

    <div class="container">
        <label>Student Id : </label>
        <input type="text" value= <?php echo $_SESSION["id"]; ?> readonly >
        <label>Name : </label>
        <input type="text" value= <?php echo $_SESSION["studentname"]; ?>  readonly>
       
    </div>
</form>
<table style="width:100%">

<tr>

<th>Course</th>
<th>Test 1</th>
<th>Test 2</th>
<th>Test 3</th>
<th>Final Exam</th>
<th>FInal Grade</th>


</tr>


<form action="main.php" method="post">
  <label for="CourseCode">Choose a Course:</label>

  <select name="CourseCode" id="CourseCode">
    <?php 
    for ($i = 0; $i < count($_SESSION['results']); $i++) {
      
    echo "<option value='" .$_SESSION['results'][$i]['CourseCode'] . "'>" .$_SESSION['results'][$i]['CourseCode'] . "</option>";
    }
      ?>
  </select>
  <label for="TestName">Choose the Test:</label>

  <select name="TestName" id="TestName">
    <option value="Test1">Test 1</option>
    <option value="Test2">Test 2</option>
    <option value="Test3">Test 3</option>
    <option value="Final">Final Exam</option>
  </select>
  <label for="marks">Update Mark:</label>
  <input type="number" id="marks" name='marks' step='1' min="0" max="100" required/>
  <button onclick="toggle(this)" type="submit" class="lohoit-button" style="background-color:green">Update</button>
</form>

<?php
function createTable(){
  for ($i = 0; $i < count($_SESSION['results']); $i++) {
    echo('<tr>');
    echo('<td>' . $_SESSION['results'][$i]['CourseCode'] . '</td>');
    echo('<td>' . $_SESSION['results'][$i]['Test1'] . '</td>');
    echo('<td>' . $_SESSION['results'][$i]['Test2'] . '</td>');
    echo('<td>' . $_SESSION['results'][$i]['Test3'] . '</td>');
    echo('<td>' . $_SESSION['results'][$i]['Final'] . '</td>');
    $finalgrade = 0.2*($_SESSION['results'][$i]['Test1'] + $_SESSION['results'][$i]['Test2'] + $_SESSION['results'][$i]['Test3'])
    + 0.4*$_SESSION['results'][$i]['Final'];
    echo('<td> '. $finalgrade .' </td>');
    echo('</tr>');
  }
}
createTable();
?>
  <!-- <tr>
    <th>Subject</th>
    <th>Grades</th>
  <tbody> 
  </tr>
  <tr>
    <td>MA103</td>
    <td>90</td>
   
  </tr>
  <tr>
    <td>CP104</td>
    <td>85</td>
    
  </tr>
  <tr>
    <td>CP164</td>
    <td>70</td>
    
  </tr>
  <tr>
    <td>Cp212</td>
    <td>86</td>
    
  </tr>
  <tr>
    <td>CP312</td>
    <td>75</td>
    
  </tr>
  <tr>
    <td>CP414</td>
    <td>72</td>
    
  </tr>
  <tr>
    <td>MA102</td>
    <td>95</td>
    
  </tr> -->
  </tbody>
  <tfoot>
    <tr>
      <td>Overall Average</td>
      <td>
        <!-- JavaScript code to calculate the total grade -->
        <script>

          var grades = document.querySelectorAll("tbody td:last-child");
              var total = 0;
              for (var i = 0; i < grades.length; i++) {
                total += parseFloat(grades[i].textContent);
              }
              var average = total / grades.length;
              document.write(average.toFixed(2) + "%");
        
        </script>
      </td>
    </tr>
  </tfoot>
</table>



</body>
</html>

<?php
error_reporting(E_ERROR | E_PARSE); #hides error and warning messages

#get connection to database
include __DIR__ . '/Helper/DotEnv.php';
(new DotEnv(__DIR__ . '/.env'))->load();
$host = 'db';
$user = 'root';
$pass = getenv('MYSQL_ROOT_PASSWORD');
$conn = new mysqli($host, $user, $pass, 'cp476');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

#prepared statement
$column = $_POST['TestName'];
$stmt = $conn->prepare("UPDATE Course_Table SET ". $column ."=? WHERE StudentID =? AND CourseCode =?");
if($stmt!==false){
  $stmt->bind_param("iis", $mark,$id, $coursecode);
  $mark = intval($_POST['marks']);
  $id = $_SESSION['id'];
  $coursecode = $_POST['CourseCode'];
  $stmt->execute();
  $stmt = $conn->prepare("SELECT CourseCode, Test1, Test2, Test3, Final FROM Course_Table WHERE StudentID =? AND CourseCode=?");
  $stmt->bind_param("ii", $id, $coursecode);
  $stmt->execute();
  $result = $stmt->get_result();
  
  #get each line from query and push to rows array
  $rows = [];
  while($row = mysqli_fetch_array($result)){
      array_push($rows, $row);
  }
  $_SESSION['results'] = $rows; #store rows in post variable
  $page = $_SERVER['PHP_SELF'];
  $sec = "10";
  header("Refresh: $sec; url=$page");

  #todo: needs a refresh to show new updated value, fix if possible
}

