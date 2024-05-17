create database library;

use library;

CREATE TABLE GeneralUser
(
  UserId INT NOT NULL,
  firstName INT NOT NULL,
  lastName INT NOT NULL,
  email INT NOT NULL,
  username INT NOT NULL,
  password INT NOT NULL,
  gender INT NOT NULL,
  PRIMARY KEY (UserId),
  UNIQUE (email),
  UNIQUE (username),
  UNIQUE (password)
);

CREATE TABLE BookCategory
(
  BookCategoryId INT NOT NULL,
  Quantity INT NOT NULL,
  PRIMARY KEY (BookCategoryId)
);

CREATE TABLE Book
(
  BookId INT NOT NULL,
  ISBN INT NOT NULL,
  name INT NOT NULL,
  description INT NOT NULL,
  bookURL INT NOT NULL,
  supplierName INT NOT NULL,
  UserId INT,
  PRIMARY KEY (BookId),
  FOREIGN KEY (UserId) REFERENCES GeneralUser(UserId),
  UNIQUE (ISBN),
  UNIQUE (bookURL)
);

CREATE TABLE Author
(
  authorId INT NOT NULL,
  fullName INT NOT NULL,
  PRIMARY KEY (authorId)
);

CREATE TABLE Personnel
(
  PersonnelId INT NOT NULL,	
  username INT NOT NULL,
  email INT NOT NULL,
  gender INT NOT NULL,
  firstName INT NOT NULL,
  lastName INT NOT NULL,
  password INT NOT NULL,
  Role INT NOT NULL,
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