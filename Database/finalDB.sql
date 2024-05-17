create database library;

use library;

CREATE TABLE GeneralUser
(
  UserId INT NOT NULL auto_increment,
  firstName VARCHAR(100) NOT NULL,
  lastName VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(100) NOT NULL,
  gender ENUM('Male', 'Female') NOT NULL,
  PRIMARY KEY (UserId),
  UNIQUE (email),
  UNIQUE (username),
  UNIQUE (password)
);

CREATE TABLE BookCategory
(
  BookCategoryId INT NOT NULL auto_increment,
  Category varchar(100) not null,
  Quantity INT NOT NULL,
  PRIMARY KEY (BookCategoryId)
);

CREATE TABLE Book
(
  BookId INT NOT NULL auto_increment,
  ISBN VARCHAR(30) NOT NULL,
  name VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  bookURL VARCHAR(255) NOT NULL,
  supplierName VARCHAR(100) NOT NULL,
  UserId INT,
  PRIMARY KEY (BookId),
  FOREIGN KEY (UserId) REFERENCES GeneralUser(UserId),
  UNIQUE (ISBN),
  UNIQUE (bookURL)
);

CREATE TABLE Author
(
  authorId INT NOT NULL auto_increment,
  firstName VARCHAR(100) NOT NULL,
  lastName varchar(100) not null,
  PRIMARY KEY (authorId)
);

CREATE TABLE Personnel
(
  PersonnelId INT NOT NULL auto_increment, 
  username VARCHAR(50) NOT NULL,
  email VARCHAR(255) NOT NULL,
  gender ENUM('Male', 'Female') NOT NULL,
  firstName VARCHAR(100) NOT NULL,
  lastName VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
   Role ENUM('Admin', 'Librarian') NOT NULL,
  PRIMARY KEY (PersonnelId),
  UNIQUE (username),
  UNIQUE (email),
  UNIQUE (password)
);

CREATE TABLE belongsTo
(
  BookCategoryId INT NOT NULL,
  BookId INT NOT NULL,
  PRIMARY KEY (BookCategoryId, BookId),
  FOREIGN KEY (BookCategoryId) REFERENCES BookCategory(BookCategoryId),
  FOREIGN KEY (BookId) REFERENCES Book(BookId)
);

CREATE TABLE hasWritten
(
  BookId INT NOT NULL,
  authorId INT NOT NULL,
  PRIMARY KEY (BookId, authorId),
  FOREIGN KEY (BookId) REFERENCES Book(BookId),
  FOREIGN KEY (authorId) REFERENCES Author(authorId)
);

CREATE TABLE manages
(
  BookId INT NOT NULL,
  PersonnelId INT NOT NULL,
  PRIMARY KEY (BookId, PersonnelId),
  FOREIGN KEY (BookId) REFERENCES Book(BookId),
  FOREIGN KEY (PersonnelId) REFERENCES Personnel(PersonnelId)
);

INSERT INTO GeneralUser (firstName, lastName, email, username, password, gender)
VALUES ('John', 'Doe', 'john.doe@example.com', 'johndoe', 'password123', 'Male');

INSERT INTO BookCategory (Category,Quantity)
VALUES ("Fantasy", 10);

INSERT INTO Book (ISBN, name, description, bookURL, supplierName, UserId)
VALUES ('978-3-16-148410-0', 'Introduction to SQL', 'A comprehensive guide to SQL programming.', 'https://m.media-amazon.com/images/I/712cDO7d73L._AC_UF1000,1000_QL80_.jpg', 'Supplier', 1);

INSERT INTO Author (firstName, lastName)
VALUES ('Jane','Smith');

INSERT INTO Personnel (username, email, gender, firstName, lastName, password, Role)
VALUES ('admin', 'admin@example.com', 'Female', 'Admin', 'User', 'admin123', 'Admin');

INSERT INTO belongsTo (BookCategoryId, BookId)
VALUES (1, 1);

INSERT INTO hasWritten (BookId, authorId)
VALUES (1, 1);

INSERT INTO manages (BookId, PersonnelId)
VALUES (1, 1);

select * from book;
select * from BookCategory;
select * from generalUser;
select * from author;
select * from Personnel;
select * from belongsTo;
select * from hasWritten;
select * from manages;
