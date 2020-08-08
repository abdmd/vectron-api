
## Vectron API Application
  

Developed as the backend of a web application requested by Vectron Systems for a coding challenge.

  
### Setup
1. Clone this repo to your laravel project path.

2. Navigate to project directory, run `composer install`

3. Configure DB connection and rename `.env.example` to `.env` file.

4. Migrate & Seed database.

	`php artisan migrate --seed`

5. Create passport clients to generate access tokens

	`php artisan passport:install`

  ### Routes
  **Users**
| Action | Path |
|--|--|
| Register | POST api/auth/register|
| Login | POST api/auth/login |
| Logout | GET api/auth/logout |
| Current User | GET api/auth/user
| Update User | PUT api/auth/user |
| Change Password | PUT api/auth/user/password |
| Delete User | DELETE api/auth/user |

Postman collection:
https://www.getpostman.com/collections/c7626614435dde85d7a2

Author:
Abdullah Mohamed Didi