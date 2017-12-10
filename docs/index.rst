SLDB - Simple Lightweight Database Controller
=============================================

.. toctree::
   :maxdepth: 2
   :caption: The Basics:

   ./basics/getting-started
   ./basics/query-types

**This documentation is currently under construction and accuracy is not guaranteed.**

SLDB is a simple lightweight database controller that provides a way to write complicated queries fast and efficiently for multiple database types in PHP. The primary goal of SLDB is to simplify database query writing as well as provide abstraction for various database types without the user having to do any heavy lifting.

The Basics
----------

To get started with SLDB, you simply need to include the *SLDB\SLDB* namespace and pass a valid configuration to the SLDB constructor during object initialization. 

.. code-block:: php
    :linenos:

    <?php

    use SLDB\SLDB;

    $database = new SLDB(array(
    	'database_type'  =>  'mysql',
    	'database_name'  =>  'mydatabase',
    	'database_user'  =>  'myuser',
    	'database_host'  =>  'localhost',
    	'database_pass'  =>  'secret',
    ));

    $result = $database->select(
    'orders',
    array(
    	'order_id',
    	'first_name',
    	'date_pickup',
    ),
    array(
    	'date_ordered' => '2017-11-15',
    ),15);

    print_r($results);

The above example will create a SLDB object, connect SLDB to a database, and run a *select* query returning the fields *order_id*, *first_name*, and *date_pickup* from the *orders* table where the *date_ordered* field is equal to *1027-11-15* with a *limit* of 15 rows returned.

Contributing
------------
SLDB is an open source project built under the MIT license, and anyone is welcome to contribute. Before you submit a pull request, be sure to read the contributor guide. All contributions are expected to conform with standard SLDB code style, documentation, and testing.

To contribute, make a local copy of SLDB by forking our Git repository. MAke whichever changes you want to suggest, and submit a pull request via GitHub.

Bug Reporting
-------------
To submit a bug report, please submit a new issue to this repository. Issues will be evaluated in the order they are received. Before submitting an issue, be sure to search as someone may have already submitted an issue for the same bug.


Indices and tables
==================

* :ref:`genindex`
* :ref:`modindex`
* :ref:`search`