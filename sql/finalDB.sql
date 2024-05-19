-- Create the library database and use it
CREATE DATABASE library;
USE library;

-- Create tables
CREATE TABLE GeneralUser (
  UserId INT NOT NULL AUTO_INCREMENT,
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

CREATE TABLE BookCategory (
  BookCategoryId INT NOT NULL AUTO_INCREMENT,
  Category VARCHAR(100) NOT NULL,
  Quantity INT NOT NULL,
  PRIMARY KEY (BookCategoryId)
);

CREATE TABLE Book (
  BookId INT NOT NULL AUTO_INCREMENT,
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

CREATE TABLE Author (
  authorId INT NOT NULL AUTO_INCREMENT,
  firstName VARCHAR(100) NOT NULL,
  lastName VARCHAR(100) NOT NULL,
  PRIMARY KEY (authorId)
);

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
  UNIQUE (email),
  UNIQUE (password)
);

CREATE TABLE belongsTo (
  BookCategoryId INT NOT NULL,
  BookId INT NOT NULL,
  PRIMARY KEY (BookCategoryId, BookId),
  FOREIGN KEY (BookCategoryId) REFERENCES BookCategory(BookCategoryId),
  FOREIGN KEY (BookId) REFERENCES Book(BookId)
);

CREATE TABLE hasWritten (
  BookId INT NOT NULL,
  authorId INT NOT NULL,
  PRIMARY KEY (BookId, authorId),
  FOREIGN KEY (BookId) REFERENCES Book(BookId),
  FOREIGN KEY (authorId) REFERENCES Author(authorId)
);

CREATE TABLE manages (
  BookId INT NOT NULL,
  PersonnelId INT NOT NULL,
  PRIMARY KEY (BookId, PersonnelId),
  FOREIGN KEY (BookId) REFERENCES Book(BookId),
  FOREIGN KEY (PersonnelId) REFERENCES Personnel(PersonnelId)
);

-- Insert GeneralUser data
INSERT INTO GeneralUser (firstName, lastName, email, username, password, gender) VALUES
('John', 'Doe', 'john.doe@example.com', 'johndoe', 'password123', 'Male'),
('Charlie', 'Brown', 'charlie.brown@example.com', 'charlieb', 'charlie123', 'Male'),
('Dana', 'White', 'dana.white@example.com', 'danawhite', 'dana123', 'Female'),
('Alice', 'Johnson', 'alice.johnson@example.com', 'alicejohnson', 'alice123', 'Female'),
('Bob', 'Williams', 'bob.williams@example.com', 'bobwilliams', 'bob123', 'Male');

-- Insert Personnel data
INSERT INTO Personnel (username, email, gender, firstName, lastName, password, Role) VALUES
('librarian3', 'librarian3@example.com', 'Female', 'Emma', 'Wilson', 'lib12345', 'Librarian'),
('admin2', 'admin2@example.com', 'Male', 'Ethan', 'Thomas', 'admin1234', 'Admin'),
('admin', 'admin@example.com', 'Female', 'Admin', 'User', 'admin123', 'Admin');

-- Insert BookCategory data
INSERT INTO BookCategory (Category, Quantity) VALUES
('Fantasy', 10),
('Mystery', 12),
('Biography', 6),
('Science Fiction', 8),
('History', 5),
('Comedy', 10),
('True Crime', 5);

-- Insert Book data
INSERT INTO Book (ISBN, name, description, bookURL, supplierName, UserId) VALUES
('978-3-16-148410-0', 'Introduction to SQL', 'A comprehensive guide to SQL programming.', 'https://m.media-amazon.com/images/I/712cDO7d73L._AC_UF1000,1000_QL80_.jpg', 'Supplier', 1),
('978-0-7432-7356-5', 'The Da Vinci Code', 'A mystery thriller novel by Dan Brown.', 'https://upload.wikimedia.org/wikipedia/en/6/6b/DaVinciCode.jpg', 'Thriller Books Supplier', 4),
('978-0-06-231500-7', 'Steve Jobs', 'A biography of Steve Jobs by Walter Isaacson.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1511288482i/11084145.jpg', 'Biography Books Supplier', 5),
('978-0-452-28423-6', 'Brave New World', 'A dystopian social science fiction novel by Aldous Huxley.', 'https://upload.wikimedia.org/wikipedia/en/6/62/BraveNewWorld_FirstEdition.jpg', 'Classic Books Supplier', 1),
('978-1-5011-7687-6', 'Becoming', 'A memoir by Michelle Obama.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1528206996i/38746485.jpg', 'Memoir Books Supplier', 2),
('978-0-7432-7357-2', 'Angels and Demons', 'A mystery-thriller novel by Dan Brown.', 'https://d28hgpri8am2if.cloudfront.net/book_images/onix/cvr9780743493468/angels-demons-9780743493468_hr.jpg', 'Thriller Books Supplier', 4),
('978-0-7432-7358-9', 'The Lost Symbol', 'A mystery-thriller novel by Dan Brown.', 'https://i.gr-assets.com/images/S/compressed.photo.goodreads.com/books/1534070883l/6411961.jpg', 'Thriller Books Supplier', 4),
('978-0-345-39180-3', 'A Game of Thrones', 'A fantasy novel by George R.R. Martin.', 'https://harpercollins.co.uk/cdn/shop/products/x500_e2c69bde-fcd6-4411-b48f-eff6f6e57576.jpg?v=1702372774&width=350', 'Fantasy Books Supplier', 1),
('978-0-452-28425-0', 'Animal Farm', 'A satirical allegorical novella by George Orwell.', 'https://m.media-amazon.com/images/I/516IlJUkG6L.jpg', 'Dystopian Books Supplier', 3),
('978-0-7432-7359-6', 'Inferno', 'A mystery-thriller novel by Dan Brown.', 'https://upload.wikimedia.org/wikipedia/en/thumb/b/bb/Inferno-cover.jpg/200px-Inferno-cover.jpg', 'Thriller Books Supplier', 4),
('978-0-671-04163-3', 'To Kill a Mockingbird', 'A novel by Harper Lee.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/To_Kill_a_Mockingbird_%28first_edition_cover%29.jpg/1200px-To_Kill_a_Mockingbird_%28first_edition_cover%29.jpg', 'Classic Books Supplier', 2),
('978-0-399-16523-4', 'The Road', 'A novel by Cormac McCarthy.', 'https://upload.wikimedia.org/wikipedia/commons/2/27/The-road.jpg', 'Classic Books Supplier', 4),
('978-0-307-95685-5', 'The Girl with the Dragon Tattoo', 'A mystery novel by Stieg Larsson.', 'https://i.gr-assets.com/images/S/compressed.photo.goodreads.com/books/1684638853l/2429135.jpg', 'Mystery Books Supplier', 4),
('978-0-316-76948-9', 'The Shining', 'A horror novel by Stephen King.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1353277730i/11588.jpg', 'Horror Books Supplier', 3),
('978-0-14-017739-8', 'Of Mice and Men', 'A novella by John Steinbeck.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTgza08-gzZSrYxgAbR2LT8bK37NpuFVuhW8nMfNp5Cpg&s', 'Classic Books Supplier', 2);

-- Insert Author data
INSERT INTO Author (firstName, lastName) VALUES
('Jane', 'Smith'),
('Dan', 'Brown'),
('Walter', 'Isaacson'),
('Aldous', 'Huxley'),
('Michelle', 'Obama'),
('George R.R.', 'Martin'),
('Harper', 'Lee'),
('Cormac', 'McCarthy'),
('Stieg', 'Larsson'),
('Stephen', 'King'),
('John', 'Steinbeck');

-- Insert belongsTo data
INSERT INTO belongsTo (BookCategoryId, BookId) VALUES
(1, 1),
(1, 2),
(3, 3),
(4, 4),
(4, 5),
(2, 6),
(4, 7),
(1, 8),
(1, 9),
(1, 10),
(3, 11),
(3, 12),
(3, 13),
(5, 14),
(6, 15);

-- Insert hasWritten data
INSERT INTO hasWritten (BookId, authorId) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 2),
(7, 2),
(8, 6),
(9, 7),
(10, 2),
(11, 8),
(12, 9),
(13, 10),
(14, 10),
(15, 11);

-- Insert manages data
INSERT INTO manages (BookId, PersonnelId) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 2),
(5, 2),
(6, 3),
(7, 3),
(8, 3),
(9, 2),
(10, 1),
(11, 2),
(12, 1),
(13, 2),
(14, 1),
(15, 3);

-- Verify the inserts
SELECT * FROM book;
SELECT * FROM BookCategory;
SELECT * FROM GeneralUser;
SELECT * FROM Author;
SELECT * FROM Personnel;
SELECT * FROM belongsTo;
SELECT * FROM hasWritten;
SELECT * FROM manages;