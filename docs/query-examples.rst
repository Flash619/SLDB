.. _query-examples:

Query Examples
==============

MySQL
-----

These code snipits outline basic examples of MySQL queries being ran through SLDB. Take note of commends in the code which display the original MySQL syntax that would have been required to run the same query. Also take note of special characters being used to switch operators within the queries.

Selecting Data
++++++++++++++

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

    //SELECT order_id,first_name,date_pickup FROM orders WHERE date_ordered = '2017-11-15' LIMIT 15
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

Inserting Data
++++++++++++++

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

    //INSERT INTO orders (first_name,last_name,total) VALUES (john,doe,'25.17')
    $result = $database->insert(
       'orders',
   		array(
    		'first_name' => 'john',
    		'last_name' => 'doe',
    		'total' => '25.17',
    	));

    //Print results to page.
    print_r($results);

Updating Data
+++++++++++++

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

    //UPDATE orders WHERE order_id=127 SET first_name='jane' WHERE first_name LIKE 'john' AND last_name LIKE 'doe' OR last_name != 'smith' LIMIT NONE;
    $result = $database->update(
       'orders',
   		array(
    		'first_name' => '[l]john',
    		'last_name' => array('[l]doe','||','[!=]smith'),
    	),
    	array(
    		'first_name' => 'jane',
    	));

    //Print results to page.
    print_r($results);

Deleting Data
+++++++++++++

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

    //DELETE FROM orders WHERE first_name='jane' AND last_name LIKE 'doe' LIMIT 25
    $result = $database->update(
       'orders',
   		array(
    		'first_name' => 'jane',
    		'last_name' => '[l]doe',
    	),
    	25);

    //Print results to page.
    print_r($results);