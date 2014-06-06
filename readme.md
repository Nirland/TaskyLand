# Tasky Land

## Decription
TaskyLand is a RESTful application which implementing simple task tracker API:
- CRUD operations with users, projects, tasks and task progress(like a comment/report on the work done). 
- Support access control based on user roles and project membership.
- Creation of two kind reports with charts.

Also started the development of the SPA client, which is at a very early stage, but already implements authorization and client side access control.

## Technical Information
- Server side implementation written on PHP 5.3 and uses Laravel 4 framework and MySQL(MariaDB).
- API returns data in JSON format.
- API receive and process simple HTTP request parameters.
- API uses basic HTTP autorization.
- Client side implementation is a single page application written on JavaScript and uses AngularJS framework.

## System requirments
- PHP 5.3+
- MariaDB or MySQL 5.1+ 

## Installation 
1. You need to create database and edit database config in TaskyLand/app/config/database.php.
2. If you wish, you can modify database seeder in TaskyLand/app/database/seeds/AllTablesSeeder.php 
3. Use CLI command <b>php artisan migrate:refresh --seed</b> for run migrations and seeding database
4. Use CLI command <b>php artisan serve</b> for run application on built in PHP server.
5. For testing API services you can use CURL or browser addon, eg Chrome [DHC](https://www.sprintapi.com/dhcs.html) 

## Testing
### API
For testing api you can use test accounts(Account:Password): Nirland:123 or Telin:123. 
Also you can modify database seeder for your start accounts.<br>
Most of HTTP clients support basic auth.<br> 
You must send HTTP header <b>Authorization:Basic base64_encode(Account:Password)</b>, if you work manually.<br>
Also you must send HTTP header <b>Content-Type:application/x-www-form-urlencoded</b> for POST and PUT requests.<br>
API basic route is <b>http://localhost:8000/api</b>.
You can use HTTP methods(GET, POST, PUT, DELETE) to access to these routes:
- <b>/user/{user_id?}</b>
- <b>/user/{user_id}/project</b>
- <b>/user/{user_id}/task</b>
- <b>/user/{user_id}/progress</b>
- <b>/project/{project_id?}</b>
- <b>/project/{project_id}/task/{task_id?}</b>
- <b>/project/{project_id}/task/{task_id}/progress/{progress_id?}</b>
- <b>/task/{task_id}</b>
- <b>/progress/{progress_id}</b>
- <b>/auth</b>

For example <b>GET http://localhost:8000/api/project/1/task</b> returns JSON response, which contains project tasks. 
Also service support many request parameters for selecting and filtering entities.
For more information about routes and request parameters you can read sources TaskyLand/app/routes.php and TaskyLand/app/controllers.

### Reports
Reports is a normal html pages and accessable via browser. 
- <b>http://localhost:8000/report/user/{user_id}/{project_id?}</b>
- <b>http://localhost:8000/report/project{project_id}</b>

