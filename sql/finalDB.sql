create database library;
use library;

CREATE TABLE User
(
  firstName VARCHAR(100) NOT NULL,
  lastName VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  gender ENUM('Male', 'Female') NOT NULL, 
  UserId INT NOT NULL AUTO_INCREMENT,
  Role VARCHAR(20) NOT NULL,
  PRIMARY KEY (UserId),
  UNIQUE (email),
  UNIQUE (username)
);

CREATE TABLE Book
(
  ISBN varchar(30) NOT NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT NOT NULL,
  bookURL VARCHAR(255) NOT NULL,
  BookId INT NOT NULL AUTO_INCREMENT,
  supplierName VARCHAR(100) NOT NULL,
  Quantity INT NOT NULL,
  PRIMARY KEY (BookId),
  UNIQUE (ISBN),
  UNIQUE (bookURL)
);

CREATE TABLE Author
(
  firstName VARCHAR(100) NOT NULL,
  authorId INT NOT NULL AUTO_INCREMENT,
  lastName VARCHAR(100) NOT NULL,
  PRIMARY KEY (authorId)
);

CREATE TABLE BookCategory
(
  Quantity INT NOT NULL,
  Category VARCHAR(100) NOT NULL,
  BookCategoryId INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (BookCategoryId)
);

CREATE TABLE hasWritten
(
  BookId INT NOT NULL,
  authorId INT NOT NULL,
  PRIMARY KEY (BookId, authorId),
  FOREIGN KEY (BookId) REFERENCES Book(BookId) ON DELETE CASCADE,
  FOREIGN KEY (authorId) REFERENCES Author(authorId) ON DELETE CASCADE 
);

CREATE TABLE borrows
(
  BorrowedDate DATE NOT NULL,
  Status enum('requested','borrowed','returned','overdue') NOT NULL,
  BookId INT NOT NULL,
  UserId INT NOT NULL,
  PRIMARY KEY (BookId, UserId),
  FOREIGN KEY (BookId) REFERENCES Book(BookId) ON DELETE CASCADE,
  FOREIGN KEY (UserId) REFERENCES User(UserId) ON DELETE CASCADE
);

CREATE TABLE belongsTo
(
  BookId INT NOT NULL,
  BookCategoryId INT NOT NULL,
  PRIMARY KEY (BookId, BookCategoryId),
  FOREIGN KEY (BookId) REFERENCES Book(BookId) ON DELETE CASCADE,
  FOREIGN KEY (BookCategoryId) REFERENCES BookCategory(BookCategoryId) ON DELETE CASCADE
);

INSERT INTO User (firstName, lastName, email, username, password, gender, Role) VALUES
('John', 'Doe', 'john.doe@example.com', 'johndoe', '87efb81e8f01b77b0a8be67c5ec785c6', 'Male', 'user'), -- pass0
('Jane', 'Smith', 'admin@example.com', 'janesmith', '949af44980f54ae908b97bdf53ed507a', 'Female', 'admin'), -- admin123
('Alice', 'Johnson', 'alice.johnson@example.com', 'alicej', 'fafc3ce7730c9e58e77f801ac18466c9', 'Female', 'user'), -- pass1
('Bob', 'Brown', 'bob.brown@example.com', 'bobb', '95ed523d77a5247545a5484c67f71577', 'Male', 'user'), -- pass3
('Carol', 'White', 'carol.white@example.com', 'carolw', '7a8157ae2409c3c752b8b4ec5209c90c', 'Female', 'user'), -- pass4
('Adam', 'Joe', 'adam.joe@example.com', 'adamjoe', 'b1469793c545629755baeec3cb40e56f', 'Male', 'librarian'), -- pass5
('James', 'Bond', 'james.bond@example.com', 'jamesbond', 'f8766c850f2cd295d2b4bbb659723c82', 'Male', 'librarian'); -- pass6

INSERT INTO Book (ISBN, title, description, bookURL, supplierName, Quantity) VALUES
('9781234567890', 'Book One', 'Description for Book One', 'https://m.media-amazon.com/images/I/71k--OLmZKL._AC_UF894,1000_QL80_.jpg', 'Supplier One', 10),
('9781234567891', 'Book Two', 'Description for Book Two', 'https://www.pluggedin.com/wp-content/uploads/2020/01/hobbit-cover-670x1024.jpg', 'Supplier Two', 5),
('9781234567892', 'Book Three', 'Description for Book Three', 'https://m.media-amazon.com/images/I/712cDO7d73L._AC_UF1000,1000_QL80_.jpg', 'Supplier One', 7),
('9781234567893', 'Book Four', 'Description for Book Four', 'https://m.media-amazon.com/images/I/81q77Q39nEL._AC_UF894,1000_QL80_.jpg', 'Supplier Three', 3),
('9781234567894', 'Book Five', 'Description for Book Five', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1663805647i/136251.jpg', 'Supplier Two', 12);

INSERT INTO Author (firstName, lastName) VALUES
('Mark', 'Twain'),
('Jane', 'Austen'),
('Charles', 'Dickens'),
('J.K.', 'Rowling'),
('Ernest', 'Hemingway');

INSERT INTO BookCategory (Quantity, Category) VALUES
(50, 'Fiction'),
(30, 'Non-Fiction'),
(20, 'Science'),
(40, 'History'),
(10, 'Biography');

INSERT INTO hasWritten (BookId, authorId) VALUES
(1, 1), -- Book One written by Mark Twain
(2, 2), -- Book Two written by Jane Austen
(3, 3), -- Book Three written by Charles Dickens
(4, 4), -- Book Four written by J.K. Rowling
(5, 5); -- Book Five written by Ernest Hemingway

INSERT INTO borrows (BorrowedDate, Status, BookId, UserId) VALUES
('2024-05-01', 'borrowed', 1, 1), -- John borrowed Book One
('2024-05-02', 'returned', 2, 2), -- Jane returned Book Two
('2024-05-03', 'borrowed', 3, 3), -- Alice borrowed Book Three
('2024-05-04', 'overdue', 4, 4), -- Bob has overdue Book Four
('2024-05-05', 'requested', 5, 5); -- Carol requested Book Five

INSERT INTO belongsTo (BookId, BookCategoryId) VALUES
(1, 1), -- Book One belongs to Fiction
(2, 2), -- Book Two belongs to Non-Fiction
(3, 3), -- Book Three belongs to Science
(4, 4), -- Book Four belongs to History
(5, 5); -- Book Five belongs to Biography

select * from author;
select * from belongsTo;
select * from hasWritten;
select * from book;
select * from bookCategory;
select * from user;
select * from borrows;

use library;

-- drop database library;


