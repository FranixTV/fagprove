# Fagprøve
Fagprøve 2021 - Rikart Svendsgård

## Introduction
### Publishing tool
Simple publishing tool written in PHP to publish, edit and delete articles. It is hidden behind a login screen and users can be created within the tool to grant access to other people.

### Article list
Simple article list used to list all the articles published by the publishing tool. It only gets published articles, and it features a readmore view of each article

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

Install Vue CLI:
```
npm install -g @vue/cli
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
There are 2 sites for this project, as the publishing tool and the article list are separated.

#### Publishing tool
Standard URL: http://localhost:8001

User:
```
Username: admin
Password: admin
```

Create articles by clicking the "Ny artikkel" button at the top when logged in. Edit articles by clicking "Rediger" on an article, or delete it by clicking "Slett". Users are exactly the same except you can only add/remove users. To add a user click the "Ny bruker" button, and to delete it click "Slett". All articles written by a user will automatically be deleted upon deleting the user.

#### Article list
Standard URL: http://localhost:8080

The articles are retrieved by an API call to the publishing tool. If the URL to the publishingtool does not match the standard URL, the code has to be changed before the article list will work.

Fix:
- In `spa/src/App.vue` find (line 35):
```js
getArticles: function () {
  fetch('http://localhost:8001/API/articles', {
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json"
    }})
    .then(response => response.json())
    .then((data) => {
      this.articles = data;
      setTimeout(() => {
        this.showFooter = true;
      }, 500);
    });
}
```
- Replace the URL in the `fetch` method to the correct URL for the publishing tool (don't change `/API/articles`)
