-- Create books table
CREATE TABLE IF NOT EXISTS books (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    genre VARCHAR(100),
    publisher VARCHAR(255),
    rating DECIMAL(3,1),
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create an index on the title for faster searches
CREATE INDEX IF NOT EXISTS idx_books_title ON books(title);

-- Create an index on the author for faster searches
CREATE INDEX IF NOT EXISTS idx_books_author ON books(author); 