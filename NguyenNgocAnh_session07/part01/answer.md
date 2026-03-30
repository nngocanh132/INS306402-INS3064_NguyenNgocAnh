
1. JOIN Distinction**
`INNER JOIN` returns only the rows that have matching values in both tables. If a record in the left table has no match in the right table, it will not appear in the result.
`LEFT JOIN` returns all rows from the left table, even if there is no matching record in the right table. In that case, the columns from the right table will be `NULL`. 

2. Aggregation Logic
The `HAVING` clause is used to filter grouped data after applying `GROUP BY`. It is typically used with aggregate functions such as `SUM()` or `COUNT()`.
The `WHERE` clause cannot be used for this purpose because it filters rows before grouping, so aggregate values are not yet available. 

3. PDO Definition
PDO stands for **PHP Data Objects**.
Two advantages of PDO over mysqli are:
First, it supports prepared statements, which improve security.
Second, it is database-independent, meaning it can work with different database systems, not just MySQL. 

4. Security
Prepared Statements protect against SQL Injection by separating the SQL query from user input.
The query is first defined with placeholders (such as `?` or `:name`), and then the user input is bound to these placeholders. This ensures that the input is treated strictly as data, not as part of the SQL command. 

5. Execution Flow
The typical execution order is:
`WHERE` → `GROUP BY` → `HAVING`. 
