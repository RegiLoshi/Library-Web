<!-- TO-DO
1) SESSIONS
2) AFTER WELCOME H1 TAG TO BE ADDED DYNAMIC USER USERNAME (SESSION HAS TO BEEN DONE)
3) LOG OUT IN NAV BAR TO DIRECT TO SIGN OUT PAGE AND DESTROY SESSION
4) VIEW DETAILS AJAX REQUEST TO BE FINISHED WHEN VIEWDETAILS PAGE IS FINISHED
-->
<!-- GET ALL BOOKS -->
<?php
include 'header.php';
?>

<html>
<head>
        <title>Library</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/mainview.css">
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <!-- BOOSTRAP -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>

<body>
<!-- NAVIGATION BAR -->
<nav>
  <ul>
    <li>
      <a href="MainView.php">Home</a>
    </li>
    <li>
      <a href="">User</a>
    </li>
    <li>
      <a href="logout.php" id="signOut">Sign Out</a>
    </li>
  </ul>
  </nav>
  <!-- SEARCH -->
    <div class="container py-3">
    <form action="MainView.php" method="GET">
        <div class="input-group">
        <input type="text" class="form-control" placeholder="Search for books..." name="search">
        <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>
    </div>
    

    <!-- BOOKS -->
    <section style="background-color: #eee;">
  <div class="text-center container py-5">
    <h4 class="mt-4 mb-5"><strong>Available Books</strong></h4>
    <div class="row">
      <?php
        require_once('config.php');

        //NUMBER OF BOOKS PER PAGE
        $booksPerPage = 6;

        // Get the current page number from the URL parameter
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        // Calculate the offset based on the current page and number of books per page
        $offset = ($page - 1) * $booksPerPage;

        // get books data from db using LIMIT and OFFSET for pagination , ALSO NAME WILL BE EITHER EMPTY OR NOT DEPENDING ON SEARCH
        $search = isset($_GET['search']) ? $_GET['search'] : '';

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
                    Book.name LIKE '%$search%'
                LIMIT $offset, $booksPerPage";


        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $cnt = 1;
          while($row = $result->fetch_assoc()) {
            $isbn = $row["ISBN"];
            $book_name = $row["name"];
            $description = $row["description"];
            $image = $row["bookURL"];
            $category = $row["CategoryName"];
            $author_name = $row["AuthorName"];
      ?>
      <div class="col-lg-4 col-md-12 mb-4">
        <div class="card">
          <div class="bg-image hover-zoom ripple ripple-surface ripple-surface-light" data-mdb-ripple-color="light">
            <img src="<?php echo $image; ?>" class="w-100" style="max-width: 100%; max-height: 500px;" />
            <a href="#">
              <div class="mask">
                <div class="d-flex justify-content-start align-items-end h-100">
                  <h5><span class="badge bg-primary ms-2">New</span></h5>
                </div>
              </div>
              <div class="hover-overlay">
                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
              </div>
            </a>
          </div>
          <div class="card-body">
            <p>#<?php echo $isbn; ?></p>
            <a href="#" class="text-reset">
              <h5 class="card-title mb-3"><?php echo $book_name; ?></h5>
            </a>
            <a href="#" class="text-reset">
              <p>Category: <?php echo $category; ?></p>
            </a>
            <h6 class="mb-3">Written by: <?php echo $author_name; ?></h6>
            <button class="viewDetails" data-id="<?php echo $isbn; ?>">View Details</button>
          </div>
        </div>
      </div>
      <?php
          }
        }
      ?>
    </div>
  </div>
</section>

<!-- Pagination -->
<div class="pagination">
  <?php
  // total number of pages
  $totalPagesSql = "SELECT CEIL(COUNT(*) / $booksPerPage) AS totalPages FROM Book";
  $totalPagesResult = $conn->query($totalPagesSql);
  $totalPages = $totalPagesResult->fetch_assoc()['totalPages'];

  //  pagination links
  for ($i = 1; $i <= $totalPages; $i++) {
      $activeClass = $page == $i ? 'active' : '';
      echo "<a class='$activeClass' href='?page=$i'>$i</a>";
  }
  ?>
</div>

</body>
</html>

<script>
  // TO-DO, VIEWDETAILS WILL REDIRECT TO NEW PAGE, LOGIC : GETS ISBN AS AJAX REQUEST
  //AND THEN USES THAT TO FETCH INFO ON DETAILS PAGE
 $(".viewDetails").click(function(){
    var isbn = $(this).data("id");
    //var email = "<?php // echo $email; ?>"; should be added after login and sessions
        $.ajax({
            type: "POST",
            url: "", //url of view details file
            data: { isbnPHP: isbn }
        });
});
    </script>
</script>

<?php
include 'footer.php';
?>
