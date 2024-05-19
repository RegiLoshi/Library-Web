<?php
// Include your database connection file here
include 'config.php';

// Get the ISBN from the POST request
$isbn = $_POST['isbn'];

// Modify your SQL query accordingly
$sql = "SELECT
            Book.*,
            BookCategory.Category AS CategoryName,
            CONCAT(Author.firstName, ' ', Author.lastName) AS AuthorName
        FROM
            Book
        JOIN
            belongsTo ON Book.BookId = belongsTo.BookId
        JOIN
            BookCategory ON belongsTo.BookCategoryId = BookCategory.BookCategoryId
        JOIN
            hasWritten ON Book.BookId = hasWritten.BookId
        JOIN
            Author ON hasWritten.authorId = Author.authorId
        WHERE
            Book.ISBN = '$isbn'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $book_name = $row["name"];
    $description = $row["description"];
    $image = $row["bookURL"];
    $category = $row["CategoryName"];
    $author_name = $row["AuthorName"];

    // Prepare the HTML content
    $html_content = "<div style=\"text-align: center;\"><h1>$book_name</h1></div>";
    $html_content .= "<hr style=\"border-top: 1px solid black;\"><div style=\"text-align: center;\"><h3>Written by: $author_name</h3></div>";
    $html_content .= "<hr style=\"border-top: 1px solid black;\"><div style=\"text-align: center;\"><h3>Category: $category</h3></div>";
    $html_content .= "<hr style=\"border-top: 1px solid black;\"><div style=\"text-align: center;\"><h5>ISBN: $isbn</h5></div>";
    $html_content .= "<hr style=\"border-top: 1px solid black;\"><div style=\"text-align: center;\"><img style=\"max-height: 600px; max-width: 600px;\" src=\"$image\" alt=\"Book Cover\"></div>";
    $html_content .= "<hr style=\"border-top: 1px solid black;\"><div style=\"text-align: center;\"><p>$description</h2></p>";
    $html_content .= "<hr style=\"border-top: 1px solid black;\">";

    echo $html_content;
} else {
    echo "No details found for this book.";
}
?>
