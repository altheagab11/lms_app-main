<?php

session_start();

require_once('classes/database.php');
$con = new database();

$sweetAlertConfig = ""; //Initialize SweetAlert script variable

if (!isset($_SESSION['user_ID'])) {

  header(header: "Location: index.php");
  exit();

}

if (isset($_POST['add'])) {

  $authorFirstName = $_POST['author_FN'];
  $authorLastName = $_POST['author_LN'];
  $authorBirthYear = $_POST['author_bday'];
  $authorNationality = $_POST['author_nat'];
  $authorID = $con->addAuthor($authorFirstName, $authorLastName, $authorBirthYear, $authorNationality);

  if ($authorID) {

    $sweetAlertConfig = "
    <script>
    
    Swal.fire({
        icon: 'success',
        title: 'Author Added Successfully',
        text: 'Author has been added successfully!',
        confirmationButtontext: 'OK'
     }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'login.php'
        }
            });

    </script>";

  } else {

    $_SESSION['error'] = "Sorry, there was an error.";
    
  }

}

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="./package/dist/sweetalert2.css">
  <link rel="stylesheet" href="./poppers/css/bootstrap-icons.css">
  <title>Authors</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Library Management System (Admin)</a>
      <a class="btn btn-outline-light ms-auto active" href="add_authors.php">Add Authors</a>
      <a class="btn btn-outline-light ms-2" href="add_genres.php">Add Genres</a>
      <a class="btn btn-outline-light ms-2" href="add_books.php">Add Books</a>
      <div class="dropdown ms-2">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle"></i> <!-- Bootstrap icon -->
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <li>
              <a class="dropdown-item" href="profile.html">
                  <i class="bi bi-person-circle me-2"></i> See Profile Information
              </a>
            </li>
          <li>
            <button class="dropdown-item" onclick="updatePersonalInfo()">
              <i class="bi bi-pencil-square me-2"></i> Update Personal Information
            </button>
          </li>
          <li>
            <button class="dropdown-item" onclick="updatePassword()">
              <i class="bi bi-key me-2"></i> Update Password
            </button>
          </li>
          <li>
            <button class="dropdown-item text-danger" onclick="logout()">
              <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>
<div class="container my-5 border border-2 rounded-3 shadow p-4 bg-light">


  <h4 class="mt-5">Add New Author</h4>
  <form method="post" action="" novalidate>
    <div class="mb-3">
      <label for="authorFirstName" class="form-label">First Name</label>
      <input type="text" class="form-control" name="author_FN" id="authorFirstName" required>
    </div>
    <div class="mb-3">
      <label for="authorLastName" class="form-label">Last Name</label>
      <input type="text" class="form-control" name="author_LN" id="authorLastName" required>
    </div>
    <div class="mb-3">
      <label for="authorBirthYear" class="form-label">Birth Date</label>
      <input type="date" class="form-control" name="author_bday" id="authorBirthYear" max="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="mb-3">
      <label for="authorNationality" class="form-label">Nationality</label>
      <select class="form-select" name="author_nat" id="authorNationality" required>
        <option value="" disabled selected>Select Nationality</option>
        <option value="American">Filipino</option>
        <option value="American">American</option>
        <option value="British">British</option>
        <option value="Canadian">Canadian</option>
        <option value="Chinese">Chinese</option>
        <option value="French">French</option>
        <option value="German">German</option>
        <option value="Indian">Indian</option>
        <option value="Japanese">Japanese</option>
        <option value="Mexican">Mexican</option>
        <option value="Russian">Russian</option>
        <option value="South African">South African</option>
        <option value="Spanish">Spanish</option>
        <option value="Other">Other</option>
      </select>
    </div>
    <button type="submit" name= "add" class="btn btn-primary">Add Author</button>
    <script src="./package/dist/sweetalert2.js"></script>
    <?php echo $sweetAlertConfig; ?>
  </form>
</div>

<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="./package/dist/sweetalert2.js"></script>
    <?php echo $sweetAlertConfig; ?>
    
</body>
</html>