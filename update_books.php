<?php 

require_once('classes/database.php');
$con = new database();
session_start();
$sweetAlertConfig = "";

if (empty($id = $_POST['id'])) {

    header('location: index.php');

} else {

    $id = $_POST['id'];
    $data = $con->viewBooksID($id);

}

if (isset($_POST['add'])) {

  $id = $_POST['id'];
  $bookTitle = $_POST['booktitle'];
  $bookISBN = $_POST['bookisbn'];
  $bookYear = $_POST['bookyear'];
  //$bookGenres = $_POST['bookgenre'];
  $bookQuantity = $_POST['bookquan'];
  $bookID = $con->updateBooks($bookTitle, $bookISBN, $bookYear, $bookQuantity, $id);

  if ($bookID) {

    $sweetAlertConfig = "
    <script>
    
    Swal.fire({
        icon: 'success',
        title: 'Book Updated Successfully',
        text: 'Book has been updated successfully!',
        confirmationButtontext: 'OK'
     }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'admin_homepage.php'
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="./package/dist/sweetalert2.css">
  <title>Books</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Library Management System (Admin)</a>
      <a class="btn btn-outline-light ms-auto" href="add_authors.html">Add Authors</a>
      <a class="btn btn-outline-light ms-2" href="add_genres.html">Add Genres</a>
      <a class="btn btn-outline-light ms-2 active" href="add_books.html">Add Books</a>
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

  <h4 class="mt-5">Add New Book</h4>
  <form method="post" action="" novalidate>
    <div class="mb-3">
      <label for="bookTitle" class="form-label">Book Title</label>
      <input type="text" value="<?php echo $data['book_title'] ?>" class="form-control" id="bookTitle" name="booktitle" required>
    </div>
    <div class="mb-3">
      <label for="bookISBN" class="form-label">ISBN</label>
      <input type="text" value="<?php echo $data['book_isbn'] ?>" class="form-control" id="bookISBN" name="bookisbn" required>
    </div>
    <div class="mb-3">
      <label for="bookYear" class="form-label">Publication Year</label>
      <input type="number" value="<?php echo $data['book_pubyear'] ?>" class="form-control" id="bookYear" name="bookyear" required>
    </div>
    <div class="mb-3">
      <label for="bookGenres" class="form-label">Genres</label>
      <select class="form-select" value="<?php echo $data['book_genre'] ?>" id="bookGenres" name="bookgenre" multiple required>
        <option value="Fiction">Fiction</option>
        <option value="Non-Fiction">Non-Fiction</option>
        <option value="Science">Science</option>
        <option value="History">History</option>
        <option value="Biography">Biography</option>
        <option value="Fantasy">Fantasy</option>
        <option value="Mystery">Mystery</option>
        <!-- Add more genres as needed -->
      </select>
      <small class="form-text text-muted">Hold down the Ctrl (Windows) or Command (Mac) key to select multiple genres.</small>
    </div>
    <div class="mb-3">
      <label for="bookQuantity" class="form-label">Quantity Available</label>
      <input type="number" value="<?php echo $data['quantity_avail'] ?>" class="form-control" id="bookQuantity" name="bookquan" required>
    </div>
    <input type="hidden" name="id" value="<?php echo $data['book_id']; ?>">
    <button type="submit" name="add" class="btn btn-primary">Update Book</button>
  </form>
</div>
<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="./package/dist/sweetalert2.js"></script>
    <?php echo $sweetAlertConfig; ?>
</body>
</html>