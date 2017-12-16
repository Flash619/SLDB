Query Syntax
============

Query Concepts
--------------

When deciding how to structure queries, SLDB tries to make it as natural feeling as possible. To do this we tried to follow standard query syntax while adding standardization to the order of arguments required.

Queries are initialized by a call to a *query function* within the *SLDB Object*. Most basic queries will use *query functions* that are named after the queries primary operator or command. Basic queries such as select, insert, delete, and update can be called by their corresponding *query functions*. 

Query Types
-----------

Select
++++++

.. class:: SLDB

	.. method:: SLDB::select($table, $fields, $where)
				SLDB::select(table, $fields, $where, $limit)
				SLDB::select($table, $fields, $where, $limit, $offset)

				:param string $table: Table to query.
				:param array $fields: Field names to return.
				:param array $where: Field name/value pairs required to select.
				:param integer $limit: Limit the number of rows returned to this value.
				:param integer $offset: Offset the returned rows by this value.
				:returns: SLDB result array, or NULL if there is an internal error.

Insert
++++++

.. class:: SLDB

	.. method:: SLDB::insert(string $table, $values)

				:param array $values: Field name/value pairs to insert as a new row.
				:returns: SLDB result array, or NULL if there is an internal error.

Update
++++++

.. class:: SLDB

	.. method:: SLDB::update($table, $where, $values)
				SLDB::update($table, $where, $values, $limit)

				:param string $table: Table to query.
				:param array $where: Field name/value pairs to query against.
				:param array $values: Field name/value pairs to set.
				:param integer $limit: Limit the number of rows affected to this value.
				:returns: SLDB result array, or NULL if there is an internal error.

Delete
++++++

.. class:: SLDB

	.. method:: SLDB::delete($table, $where)
				SLDB::delete($table, $where, $limit)

				:param string $table: Table to query.
				:param array $where: Field name/value pairs required to delete.
				:param integer $limit: Limit the number of rows affected to this value.
				:returns: SLDB result array, or NULL if there is an internal error.
