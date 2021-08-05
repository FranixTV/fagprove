# Fagprøve
Fagprøve 2021 - Rikart Svendsgård

## Introduction
### Publishing tool
Simple publishing tool written in PHP to publish, edit and delete articles. It is hidden behind a login screen and users can be created within the tool to grant access to other people.

### Single page application
Simple single page application used to list all the articles published by the publishing tool. It only gets published articles, and it features a readmore view of each article

## Features
- CRUD functions for articles
- User controlled publishing tool
- User creation/deleting
- API
- Article list from published articles
- Readmore articles

## Getting started
### Prerequisites
- Node v12+
- PHP v7.4+
- Composer
- MySQL database

Install Vue CLI and http-server:
```
npm install -g @vue/cli http-server
```

### Installation
1. Create `.env` file using `.env.example` in `Publishingtool/` and fill the information (DB doesn't have to exist)
2. Inside `Publishingtool/` run:
```
composer install
```
3. Start simple webserver using following command in `Publishingtool/`:
```
php -S localhost:8001
```
4. Go to http://localhost:8001/start.php to generate database and admin user
5. Inside `spa/` run:
```
npm install
```
6. Open Vue GUI using `vue ui` in terminal
7. Import the `spa/` project directory in Vue GUI Project Manager
8. Choose between building and serving the project:
  - To build the project ready for production:
    1. In Vue GUI, click the "Tasks" tab
    2. Select the "build" task, and click "Run task"
    3. Start a lightweight webserver or upload `spa/dist/` to a webserver
  - To serve the project for development:
    1. In the Vue GUI, click the "Tasks" tab
    2. Select the "serve" task, and click "Run task"
    3. When it's running either go to http://localhost:8080 or click "Open app"

### Usage
There are 2 sites for this project, as the publishing tool and the single page application are separated.

#### Publishing tool
Standard URL: http://localhost:8001

User:
```
Username: admin
Password: admin
```

Create articles by clicking the "Ny artikkel" button at the top when logged in. Edit articles by clicking "Rediger" on an article, or delete it by clicking "Slett". Users are exactly the same except you can only add/remove users. To add a user click the "Ny bruker" button, and to delete it click "Slett". All articles written by a user will automatically be deleted upon deleting the user.

#### Single page application
Standard URL: http://localhost:8080

The single page application expects to retrieve articles from 
```
http://localhost:8001/API/articles
```

## API

### Endpoints
`/API/articles`

Response:
```json
[
  {
    "articleid": "1",
    "title": "Demo article",
    "summary": "This is the article summary",
    "content": "This is the main content of the article",
    "images": "[\"//localhost:8001/files/image.jpg\"]",
    "created": "2021-08-03 00:00:00",
    "username": "admin"
  }
]
```
