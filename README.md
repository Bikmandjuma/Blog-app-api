<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Klab Blog app API

> Assignment

In this assignment, you are required to create a REST API application, in the previous blog app assignment, you rendered front-end templates using blade templating engine, as a passionate back-end dev you will in the future build only APIs and sends endpoints and responses to front-end dev.
From now forget rendering things on the browser, as we learned to send JSON responses.<br><br>
> *Consult the README of the previous assignment [here](https://github.com/muhenge/Klab-blog-app), implement all tasks in API mode.*

N.B : Remember to add tasks that are not in the documentation:

     - implement like functionality: User can like an article
     - User can follow another user.
     - Implement all validations possible
## Tasks
- Create an authentication system using laravel sanctum authentication mechanism
  - on the Login process, return also a token generated choose between JWT or Sanctum token
- Url must be in this format: `localhost:8080/api/v1/`
- json must be:
  - `{ "field-name": { }", "message":""` for exemple:
    take this endpoint :  `localhost:8000/api/v1/allblogs`

    Response should be:
    `{
      "posts":{"title":"Hello","content:"Hello content....."}
    }`<br><br>

 <div align="center">Happy coding</div>
