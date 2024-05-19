create database library;
use library;

CREATE TABLE User
(
  firstName VARCHAR(100) NOT NULL,
  lastName VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  gender ENUM('M', 'F', 'O') NOT NULL, -- 'M' for Male, 'F' for Female, 'O' for Other
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

<<<<<<< HEAD
CREATE TABLE BookCategory
(
  Quantity INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  BookCategoryId INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (BookCategoryId)
=======
CREATE TABLE Personnel (
  PersonnelId INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(255) NOT NULL,
  gender ENUM('Male', 'Female') NOT NULL,
  firstName VARCHAR(100) NOT NULL,
  lastName VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  Role ENUM('Admin', 'Librarian') NOT NULL,
  PRIMARY KEY (PersonnelId),
  UNIQUE (username),
  UNIQUE (email)
>>>>>>> eac0b46ad21361ffcead45ee8e15b9f6be3e1668
);

CREATE TABLE hasWritten
(
  BookId INT NOT NULL,
  authorId INT NOT NULL,
  PRIMARY KEY (BookId, authorId),
  FOREIGN KEY (BookId) REFERENCES Book(BookId),
  FOREIGN KEY (authorId) REFERENCES Author(authorId)
);

CREATE TABLE borrows
(
  BorrowedDate DATE NOT NULL,
  Status enum('requested','borrowed','returned','overdue') NOT NULL,
  BookId INT NOT NULL,
  UserId INT NOT NULL,
  PRIMARY KEY (BookId, UserId),
  FOREIGN KEY (BookId) REFERENCES Book(BookId),
  FOREIGN KEY (UserId) REFERENCES User(UserId)
);

<<<<<<< HEAD
CREATE TABLE belongsTo
(
  BookId INT NOT NULL,
  BookCategoryId INT NOT NULL,
  PRIMARY KEY (BookId, BookCategoryId),
  FOREIGN KEY (BookId) REFERENCES Book(BookId),
  FOREIGN KEY (BookCategoryId) REFERENCES BookCategory(BookCategoryId)
);

INSERT INTO User (firstName, lastName, email, username, password, gender, Role) VALUES
('John', 'Doe', 'john.doe@example.com', 'johndoe', 'password1', 'M', 'user'),
('Jane', 'Smith', 'jane.smith@example.com', 'janesmith', 'password2', 'F', 'admin'),
('Alice', 'Johnson', 'alice.johnson@example.com', 'alicej', 'password3', 'F', 'user'),
('Bob', 'Brown', 'bob.brown@example.com', 'bobb', 'password4', 'M', 'user'),
('Carol', 'White', 'carol.white@example.com', 'carolw', 'password5', 'F', 'user');
=======
-- Insert GeneralUser data
INSERT INTO GeneralUser (firstName, lastName, email, username, password, gender) VALUES
('John', 'Doe', 'john.doe@example.com', 'johndoe', '9e301c6087cac6016393b1ace4fa8965', 'Male'),
('Charlie', 'Brown', 'charlie.brown@example.com', 'charlieb', 'c020efea82ade269335dcd48ff8226f2', 'Male'),
('Dana', 'White', 'dana.white@example.com', 'danawhite', 'a6640f907f5b1434e0fe3e7b3478de0f', 'Female'),
('Alice', 'Johnson', 'alice.johnson@example.com', 'alicejohnson', '00d3e7625c14669fc8f6368323531ac8', 'Female'),
('Bob', 'Williams', 'bob.williams@example.com', 'bobwilliams', '81839aaf7cbf9c84cb36d0d008c605a5', 'Male');

-- Insert Personnel data
INSERT INTO Personnel (username, email, gender, firstName, lastName, password, Role) VALUES
('librarian3', 'librarian3@example.com', 'Female', 'Emma', 'Wilson', '2972afd75f5b8c27d96180aadea7d7a9', 'Librarian'),
('admin2', 'admin2@example.com', 'Male', 'Ethan', 'Thomas', '6220e31d94291328aee391ce2bf54338', 'Admin'),
('admin', 'admin@example.com', 'Female', 'Admin', 'User', '949af44980f54ae908b97bdf53ed507a', 'Admin');
>>>>>>> eac0b46ad21361ffcead45ee8e15b9f6be3e1668

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



