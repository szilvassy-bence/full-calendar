# Full Calendar

## About

This project is a full-stack web application that this application allows customers to book an appointment during the customer reception hours

The backend is `Laravel`, the database is `PostgreSQL`, the frontend is uses `React` and `FullCalendar.io` plugin.

## The application structure

### Backend
- routes/api.php accepts the requests and forwards them to BookingController
- BookingController responds, or users BookingRepository to handle the Db transactions
- BookingRepository uses BookingService for business logic for checking the open booking slots

## How to run?

### Set up the backend

- Save .env.example file as .env file:
    ```
    cd calendar-app
    copy .env.example .env
    ```
- Edit your pgsql database settings (DB_USERNAME and DB_PASSWORD)
- Create a database called `calendar_app`
- Install backend dependencies:
    ```
      cd calendar-app
      composer install  
    ```
- Perform migration and seeding:
    ```
      php artisan migrate:fresh --seed
    ```
- Run the application:
    ```
      php artisan serve
    ```

### Set up the frontend
- Install frontend dependencies:
    ```
    npm install
    ```
- Run the application:
    ```
      npm run dev
    ```

## Further improvement opportunities
- Implementing authentication and authorization logic
- Dockerizing the application
- Implement proper frontend testing