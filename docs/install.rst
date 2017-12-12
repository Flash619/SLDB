Getting Started
===============

Installation
------------

To get started with SLDB, you simply need to include the *SLDB\\SLDB* namespace and pass a valid configuration to the SLDB constructor during object initialization. You can also install SLDB with *Composer* and let *Composer* handle dependancies, this is the prefered method of installing SLDB.

Once installed, SLDB can be used as seen below.

.. code-block:: php
    :linenos:

    <?php

    //Include SLDB Namespace
    use SLDB\SLDB;

    //Initialize SLDB object with configuration.
    $database = new SLDB(array(
    	'database_type'  =>  'mysql',
    	'database_name'  =>  'mydatabase',
    	'database_user'  =>  'myuser',
    	'database_host'  =>  'localhost',
    	'database_pass'  =>  'secret',
    ));

    //Run a basic select query and return results.
    $result = $database->select(
       'orders',
   		array(
    		'order_id',
    		'first_name',
    		'date_pickup',
    	),
    	array(
    		'date_ordered' => '2017-11-15',
    	),
    	15);

    //Print results to page.
    print_r($results);

The above example will create a SLDB object, connect SLDB to a database, and run a *select* query returning the fields *order_id*, *first_name*, and *date_pickup* from the *orders* table where the *date_ordered* field is equal to *1027-11-15* with a *limit* of 15 rows returned.

For more details and examples, it is recommended that you first read Query Basics before writing any projects.