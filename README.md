# PHP Conference 2023 - Code Repository

## Overview
This repository contains the code and resources used for my talk at the PHP Conference 2023. It's intended to support attendees by providing hands-on examples and materials discussed during the session.
It also supports the following contents: 

- Live: [Integrating ChatGPT using PHP and Vector Databases with Daniel Archer](https://www.youtube.com/watch?v=W885qNQ0MIM) (part 1)
- Live: [Integrating ChatGPT using PHP and Vector Databases with Daniel Archer](https://www.youtube.com/watch?v=g1Tzbh-oDtQ) (part 2)
- Devm.io Event: [How to Craft an OpenAI-Powered Chatbot](https://devm.io/live-events/how-to-craft-an-openai-powered-chatbot/)
- Article: [Building a Proof of Concept Chatbot with OpenAIs API, PHP and Pinecone](https://mlconference.ai/blog/building-chatbot-openai-api-php-pinecone/)

## Installation

### Prerequisites
- PHP
- Composer
- Docker

### Setup
Clone the repository and navigate to the project directory.

Install dependencies with Composer:
```bash
composer install
```
### Docker
Use Docker to set up the environment. A Dockerfile and docker-compose.yml are provided for ease of setup.
```bash
docker-compose up -d
```
### Before starting of the project, run the following commands
```bash
docker-compose exec db psql -U app -d chat -f /app/schema_pgvector.sql
docker-compose exec web php /var/www/public/8.0.populate_postgres.php
```

### Database Setup
SQL schema files are provided to set up the necessary databases.

- schema_pgvector.sql: For setting up vector data storage in PostgreSQL.
- schema_pinecone.sql: For setting up Pinecone vectors.
Execute the SQL files against your database to create the schemas.

## Project Structure
Explanation of the file structure and contents.

- `public/` - Scripts for various chat functionalities and data processing.
- `src/` - Source code for the core functionality of the chat system.
- `storage/` - Contains JSON files used for storage.
- `vendor/` - Dependencies managed by Composer.

## Usage
The chat system is accessible via the `public/index.php` file.
In order to follow the examples you can take a look at the `public/1.0.chat.php` file.
- From examples 1 to 4 you will see the **basic structure of the OpenAI API calls**.
- Examples 5 to 7 show the **embedding and similarity** search functionality.
- Example 8 and 9 shows the **Pinecone/Postgres integration**.
- Finally for the **RabbitMQ setup**, you can take a look at the `public/publisher.php` & `public/consumer.php` files.

## Contact
Please feel free to contact me if you have any questions or feedback.
More information on my Github profile.

