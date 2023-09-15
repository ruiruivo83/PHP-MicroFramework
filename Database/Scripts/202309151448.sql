-- Add a new column 'time_zones' to the 'users' table
ALTER TABLE users
ADD COLUMN time_zone JSON DEFAULT NULL;
