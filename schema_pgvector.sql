-- Enable the vector extension
CREATE EXTENSION vector;

-- Create the datasource table
CREATE TABLE IF NOT EXISTS datasource (
    id bigserial PRIMARY KEY,
    text text,
    vector vector(1536)
);
