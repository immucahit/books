-- USERS

CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  display_name VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

CREATE UNIQUE INDEX IF NOT EXISTS users_username_index ON users USING BTREE (username ASC);

-- AUTHORS

CREATE TABLE IF NOT EXISTS authors (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL
);

-- BOOKS

CREATE TABLE IF NOT EXISTS books (
  id SERIAL PRIMARY KEY,
  author_id INT DEFAULT NULL,
  user_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT DEFAULT NULL,
  isbn VARCHAR(255) NOT NULL,
  price FLOAT(8) NOT NULL,
  is_deleted BOOLEAN DEFAULT FALSE,
  CONSTRAINT fk_author FOREIGN KEY (author_id) REFERENCES authors (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE INDEX IF NOT EXISTS books_author_id_index ON books USING BTREE (author_id ASC);
CREATE INDEX IF NOT EXISTS books_user_id_index ON books USING BTREE (user_id ASC);
CREATE INDEX IF NOT EXISTS books_isbn_index ON books USING BTREE (isbn ASC);

-- LOG

CREATE TYPE "log.action" AS ENUM ('insert','update');

CREATE TABLE IF NOT EXISTS log(
  id SERIAL PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id INT NOT NULL,
  table_name VARCHAR(255) NOT NULL,
  action "log.action",
  data JSONB,
  CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE INDEX IF NOT EXISTS log_user_id_index ON log USING BTREE (user_id ASC);