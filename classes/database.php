<?php
class database{

    function opencon(): PDO{
        return new PDO(
            dsn: 'mysql:host=localhost;
            dbname=lms_app',
            username: 'root',
            password: '');
    }

    function signupUser($firstname, $lastname, $birthday, $email, $sex, $phone, $username, $password, $profile_picture_path) {

        $con = $this->opencon();
        
        try {
            $con->beginTransaction();

            $stmt = $con->prepare("INSERT INTO users (user_FN, user_LN, user_birthday, user_sex, user_email, user_phone, user_username, user_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $birthday, $sex, $email, $phone, $username, $password]);
            
            $userID = $con->lastInsertId();
            

            $stmt = $con->prepare("INSERT INTO users_pictures (user_id, user_pic_url) VALUES (?, ?)");
            $stmt->execute([$userID, $profile_picture_path]);

            $con->commit();

            return $userID;

        } catch (PDOException $e) {

            $con->rollback();
            return false;

        }

    }

    function insertAddress($userID, $street, $barangay, $city, $province) {

        $con = $this->opencon();

        try {
            $con->beginTransaction();

            $stmt = $con->prepare("INSERT INTO Address (ba_street, ba_barangay, ba_city, ba_province) VALUES (?, ?, ?, ?)");
            $stmt->execute([$street, $barangay, $city, $province]);

            $addressID = $con->lastInsertId();

            $stmt = $con->prepare("INSERT INTO users_address (user_id, address_id) VALUES (?, ?)");
            $stmt->execute([$userID, $addressID]);

            $con->commit();

            return true;

        } catch (PDOException $e) {

            $con->rollback();
            return false;

        }

    }

    function loginUser($email, $password) {

        $con = $this->opencon();

        $stmt = $con->prepare("SELECT * FROM users Where user_email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['user_password'])) {

            return $user;

        } else {

            return false;

        }

    }

    function addAuthor($authorFirstName, $authorLastName, $authorBirthYear, $authorNationality) {

        $con = $this->opencon();
        
        try {
            $con->beginTransaction();

            $stmt = $con->prepare("INSERT INTO authors (author_FN, author_LN, author_birthday, author_nat) VALUES (?, ?, ?, ?)");
            $stmt->execute([$authorFirstName, $authorLastName, $authorBirthYear, $authorNationality]);
            $authorID = $con->lastInsertId();
            $con->commit();
            return $authorID;

        } catch (PDOException $e) {

            $con->rollback();
            return false;

        }

    }

    function addGenre($genreName) {

        $con = $this->opencon();
        
        try {
            $con->beginTransaction();

            $stmt = $con->prepare("INSERT INTO genres (genre_name) VALUES (?)");
            $stmt->execute([$genreName]);
            $genreID = $con->lastInsertId();
            $con->commit();
            return $genreID;

        } catch (PDOException $e) {

            $con->rollback();
            return false;

        }

    }

    function addBook($bookTitle, $bookISBN, $bookYear, $bookQuantity, $genre_ids = [], $author_ids = []) {

         $con = $this->opencon();
        
        try {
            $con->beginTransaction();

            $stmt = $con->prepare("INSERT INTO books (book_title, book_isbn, book_pubyear, quantity_avail) VALUES (?, ?, ?, ?)");
            $stmt->execute([$bookTitle, $bookISBN, $bookYear, $bookQuantity]);
            $book_id = $con->lastInsertId();
            
            foreach ($genre_ids as $genre_id) {
                $stmt = $con->prepare("INSERT INTO Genre_Books (genre_id, book_id) VALUES (?, ?)");
                $stmt->execute([$genre_id, $book_id]);
            }

            foreach ($author_ids as $author_id) {
                $stmt = $con->prepare("INSERT INTO Book_Authors (book_id, author_id) VALUES (?, ?)");
                $stmt->execute([$book_id, $author_id]);
            }

            for ($i = 0; $i < $bookQuantity; $i++) {

                $stmt = $con->prepare("INSERT INTO Book_Copy (book_id, is_available) VALUES (?, 1)");
                $stmt->execute([$book_id]);

            }

            $con->commit();
            return $book_id;

        } catch (PDOException $e) {

            $con->rollBack();
            return false;

        }

    }

    function viewAuthors() {

        $con = $this->opencon();
        return $con->query("SELECT * FROM Authors")->fetchAll();

    }

    function viewAuthorsID($id) {

        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM Authors WHERE author_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    function updateAuthors($authorFirstName, $authorLastName, $authorBirthYear, $authorNationality, $id) {
    try {
        $con = $this->opencon();
        $con->beginTransaction();
        $query = $con->prepare("UPDATE Authors SET author_FN=?, author_LN=?, author_birthday=?, author_nat=? WHERE author_id=?");
        $query->execute([$authorFirstName, $authorLastName, $authorBirthYear, $authorNationality, $id]);
        
        $con->commit();
        return true;
    } catch (PDOException $e) {
        
         $con->rollBack();
        return false; 
    }
}

function viewGenres() {

        $con = $this->opencon();
        return $con->query("SELECT * FROM Genres")->fetchAll();

    }

function viewGenresID($id) {

        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM Genres WHERE genre_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    function updateGenres($genreName, $id) {
    try {
        $con = $this->opencon();
        $con->beginTransaction();
        $query = $con->prepare("UPDATE Genres SET genre_name=? WHERE genre_id=?");
        $query->execute([$genreName, $id]);
        
        $con->commit();
        return true;
    } catch (PDOException $e) {
        
         $con->rollBack();
        return false; 
    }
}

function viewBooks() {

        $con = $this->opencon();
        return $con->query("SELECT * FROM Books")->fetchAll();

    }

    function viewBooksID($id) {

        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM Books WHERE book_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    function updateBooks($bookTitle, $bookISBN, $bookYear, $bookQuantity, $id) {
    try {
        $con = $this->opencon();
        $con->beginTransaction();
        $query = $con->prepare("UPDATE Books SET book_title=?, book_isbn=?, book_pubyear=?, quantity_avail=? WHERE book_id=?");
        $query->execute([$bookTitle, $bookISBN, $bookYear, $bookQuantity, $id]);
        
        $con->commit();
        return true;
    } catch (PDOException $e) {
        
         $con->rollBack();
        return false; 
    }
}

}
?>