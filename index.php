<?php include('server.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Insert and Retrieve data from MySQL database with ajax</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <h3>Search</h3>
    <label for="search">Search here</label> 
    <input type="text" name="search" id="search" placeholder ="Search for User" />
    <button  id="searchButton">Search </button>
    <h2 id="heading">Create User</h2>
    <form id="createForm" method="POST">
        <label for="first_name">First Name </label>
        <input type="text" name="first_name" placeholder="First Name" id="first_name" required></br>

        <label for="last_name">Last Name </label>
        <input type="text" name="last_name" id="last_name" placeholder="Last Name" required></br>

        <label for="phone_no">Phone Number </label>
        <input type="number" name="phone_no" id="phone_no" placeholder="Phone Number" required></br>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Email" required></br>

        <label for="roles">Choose A Role:</label>
        <select id="roles" name="roles">
            <option value="software dev">Software Dev</option>
            <option value="data science">Data Science</option>
            <option value="front end">Front End</option>
        </select></br>

        <button type="submit" id="create">Create</button>
        <button type="submit" id="update" style="display: none;">Update</button>
        <input type="hidden" id="editId">
    </form>

    <h2>Users List</h2>
    <table id="userTable" border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Role</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <!-- User rows will be inserted here by AJAX -->
        </tbody>
        
    </table>
    <div id="pagination">
    <button id="prevPage">Previous</button>
    <span id="pageInfo"></span>
    <button id="nextPage">Next</button>
</div>
</body>

</html>
<!-- Add JQuery -->
<script src="./JQuery/jquery-3.7.1.min.js"></script>
<script src="./script.js"></script>