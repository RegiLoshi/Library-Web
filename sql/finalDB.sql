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
  name VARCHAR(100) NOT NULL,
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
  Status enum('requested','borrowed','returned') NOT NULL,
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
('John', 'Doe', 'john.doe@example.com', 'johndoe', '9e301c6087cac6016393b1ace4fa8965', 'Male', 'user'),
('Jane', 'Smith', 'admin@example.com', 'janesmith', '949af44980f54ae908b97bdf53ed507a', 'Female', 'admin'),
('Alice', 'Johnson', 'librarian@example.com', 'alicej', 'e6bd0dbeb2d0baee9c956b32bdf114ce', 'Female', 'librarian'),
('Bob', 'Brown', 'bob.brown@example.com', 'bobb', '81839aaf7cbf9c84cb36d0d008c605a5', 'Male', 'user'),
('Carol', 'White', 'carol.white@example.com', 'carolw', '37c350cfcc9a8c3c2c0fc31268f3e77e', 'Female', 'user');

INSERT INTO Book (ISBN, title, description, bookURL, supplierName, Quantity) VALUES
('9781234567890', 'Book One', 'Description for Book One', 'http://example.com/book1', 'Supplier One', 10),
('9781234567891', 'Book Two', 'Description for Book Two', 'http://example.com/book2', 'Supplier Two', 5),
('9781234567892', 'Book Three', 'Description for Book Three', 'http://example.com/book3', 'Supplier One', 7),
('9781234567893', 'Book Four', 'Description for Book Four', 'http://example.com/book4', 'Supplier Three', 3),
('9781234567894', 'Book Five', 'Description for Book Five', 'http://example.com/book5', 'Supplier Two', 12);

INSERT INTO Author (firstName, lastName) VALUES
('Mark', 'Twain'),
('Jane', 'Austen'),
('Charles', 'Dickens'),
('J.K.', 'Rowling'),
('Ernest', 'Hemingway');

INSERT INTO BookCategory (Quantity, name) VALUES
(50, 'Fiction'),
(30, 'Non-Fiction'),
(20, 'Science'),
(40, 'History'),
(10, 'Biography');

INSERT INTO hasWritten (BookId, authorId) VALUES
(1, 1), 
(2, 2), 
(3, 3), 
(4, 4), 
(5, 5); 

INSERT INTO borrows (BorrowedDate, Status, BookId, UserId) VALUES
('2024-05-01', 'borrowed', 1, 1), 
('2024-05-02', 'returned', 2, 2), 
('2024-05-03', 'borrowed', 3, 3), 
('2024-05-04', 'requested', 4, 4), 
('2024-05-05', 'requested', 5, 5); 

INSERT INTO belongsTo (BookId, BookCategoryId) VALUES
(1, 1), 
(2, 2), 
(3, 3), 
(4, 4), 
(5, 5); 

select * from author;
select * from belongsTo;
select * from hasWritten;
select * from book;
select * from bookCategory;
select * from user;
select * from borrows;




