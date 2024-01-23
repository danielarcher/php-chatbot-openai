
-- Create the datasource table
CREATE TABLE IF NOT EXISTS recipes (
    id bigserial PRIMARY KEY,
    name varchar(256),
    url varchar(256),
    description text,
    author varchar(256),
    ingredients text,
    method text
);
