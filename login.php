<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> GradeCheck </title>
    <style>
        Body {
            font-family: Calibri, Helvetica, sans-serif;
            background-color: white;
        }

        button {
            background-color: #4CAF50;
            width: 100%;
            color: teal;
            padding: 15px;
            margin: 10px 0px;
            border: none;
            cursor: pointer;
        }

        form {
            border: 3px solid #f1f1f1;
            display: flex;
            justify-content: center;
        }

        input[type=text],
        input[type=password] {
            width: 100%;
            margin: 8px 0;
            padding: 12px 20px;
            display: inline-block;
            border: 2px solid green;
            box-sizing: border-box;
        }

        button:hover {
            opacity: 0.7;
        }

        .cancelbtn {
            width: auto;
            padding: 10px 18px;
            margin: 10px 5px;
        }


        .container {
            padding: 25px;
            width: 40rem;
            margin: 8px 10px;

            background-color: lightblue;
        }
    </style>
</head>

<body>
    <center>
        <h1> Student Login </h1>
    </center>
    <form>
        <div class="container">
            <label>Student Id : </label>
            <input type="text" placeholder="Enter Student Id" name="id" required>
            <label>Name : </label>
            <input type="password" placeholder="Enter Name" name="name" required>
            <button type="submit">Login</button>
            <button type="button" class="cancelbtn"> Cancel</button>
            Forgot <a href="#"> id? </a>
        </div>
    </form>
</body>

</html>