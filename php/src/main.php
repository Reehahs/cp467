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
<button class="logout-button">Logout</button>    
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



<?php
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
      <td>Total</td>
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

