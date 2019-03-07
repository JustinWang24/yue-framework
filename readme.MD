# Project Documentation: Destination PHP MVC Framework
This project is build on top of pure PHP, no existed PHP framework such as CI/Laravel is used. The project is still follow the MVC architecture but implemented from zero, any other developers could refer the following guide which describe the request->response lifecycle:
- System requirements: PHP 5.6 or above; MySqli extension; URL rewrite module must be ON
- Entry point: index.php (No rewrite rule request in .htaccess, all will be into index.php)
- Routes: Support HTTP/RestFul request. Just add your route in the index.php file.
- Controllers: app/controller folder, all business logic should be in this folder
- Model: app/models folder. All database's operations should be in this folder
- View: app/views folder. All frontend templates files are in this folder; Template engine: Twig
- Core: app/core folder. The system core lib, no need to modify.
- Helpers: app/helpers folder. All helper functions should be in here, add more if you need.
- Application environment file: app/helpers/.env file. In this file, all env variables for runtime are stored here, you can append as many as you want to. At anywhere in you application, simply call evn($key) to retrieve the env variable.
- Cache: app/cache folder. For better performance, no need to modify. But this folder must be writable by PHP user.

# Dependency management and PHP class autoload
- Install all dependencies: composer install
- Update dependencies: composer update
- All classes in app/ folder will be autoload by composer, no require/include statement needed anymore
- app/helpers/general.php will be autoload by composer, add your own helper functions there

# For frontend developers
- All frontend related view files are located in app/views folder.
- Using Twig as the template engine, so no PHP knowledge is required at the frontend development
- Layouts: app/views/layout folder, the place to store reusable layout templates; Any layout file is highly recommend add 3 parts: header + content + footer AT LEAST
- To generate an URL for a/form tag, please use {{ url('place-to-go') }}. This url() helper function will make sure to generate the correct absolute URL instead of using related URL.
- To generate an URL for img/video tag, please use {{ asset('where-it-is') }}. This asset() helper function will make sure to generate the correct absolute URL instead of using relate path.
- Please make sure that all CSS resources are load in between head tag, and all js resources tags are at the bottom of the page.
- Please don't insert <script></script> code pieces in any view

# For Database operations
- Try not to write any SQL statement but use a PHP model class to do the dirty work.
- To access the database, just create a model class which extends the BaseModel. 
- To get database connection, in any BaseModel child class, use self::DB()
- CRUD has been implemented in BaseModel, please use self::save(), self::update(), self::delete(). Don't rewrite these functions, focus on the specific database query requirements.
- Please build SQL statement by using Medoo's reference. https://medoo.in/doc
- No need to set up the database schema mapping for each model. Some magic functions have been created, you can use the following way to manipulate database:
    
    

    $product = new Product();
    
    $product->find( $id = 1, ['name',['price']]);
    
    echo $product->name; // will be output product's name
    
    echo $product->price; // will be output product's price


OR


    $product = new Product(10); // Create product model instance which the ID = 10
    
    echo $product->name .': ' . $product->price; // will out put: Product name: price
    
    
OR
    
    $products = new Product()->all();
    
    foreach($product as $idx => $product){
    
        // Do what you need to to
        
    }
    
    

# For business logic
- For best practice, no database interactions in controller's class, but the business logic only
- To get the request instance, use $this->request; To get the response instance, use $this->response
- Put all variables into $this->dataForView array, this array's items will be injected into view automatically, any variable can be accessed by using the item's key
- To render the view, $this->render( 'path/to/view' );
- To retrieve the current logged in user's data from session, in any controller, do:


    $userDataArray = session_get( 'user_data_array', true );
    
    $user = new User( $userDataArray['id'] ); // Get the user instance by user's id
    
    
# Testing
- Use PHPUnit as the testing framework
- Run all test cases: composer test
- Run a specific test case: ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/MyTest

# Further Jobs: 
- Responsible design for mobile devices such as iPad, phone







