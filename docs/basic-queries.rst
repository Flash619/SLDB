Basic Queries
=============

Query Concepts
--------------

When deciding how to structure queries, SLDB tries to make it as natural feeling as possible. This being said, typically the order of arguments passed to a *query function* is *table*,*fields*,*where*,*limit*. Aditional arguments may be used or required depending on the target result. For this article, we are going to go over basic query structure as well as a few aditional arguments you may use. This article does not cover *join* or *union* as these are on the advanced queryes page.

Queries are initialized by a call to a *query function* within the *SLDB Object*. Most basic queries will use *query functions* that are named after the queries primary operator or command. Basic queries such as select, insert, delete, and update can be called by their corresponding *query functions*. Basic *query functions* are outlined below.

Baisc Query Functions
---------------------

.. php:class:: DateTime

	Datetime MyClass

	.. php:method:: select($table, $fields, $where)