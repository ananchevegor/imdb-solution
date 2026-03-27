# imdb-solution

A pet project built with **Symfony** and **PostgreSQL** to explore one core question:

**How fast can search become when working with very large databases?**

The project uses only **Symfony** and **PostgreSQL** as its main and only tools.
The `.env` files contain only local variables.
This is **not** a commercial production project.

UI is not the focus here. The main goal is to study performance, indexing, querying, pagination, and caching on large datasets.

---

## Dataset

This project is based on the **IMDb Non-Commercial Datasets**.

These datasets contain information about movies, TV shows, videos, and many other parts of global film and television production.

As expected, the database is very large. People have been making films since the 19th century, long before many modern states even existed.

The raw data is distributed in **TSV** files.
TSV is similar to CSV, but uses **tab characters** as separators.

### Dataset facts

* **7 files**
* **~17 GB total size**
* plain text format
* some tables contain **up to 98 million rows**

That may not be Google- or Amazon-scale, but it is already large enough to expose real database performance problems.

---

## The Problem

If you simply download the dataset and try to open it in a typical editor, processing will already be slow depending on your machine.

After importing the data into PostgreSQL, the total table size is still around **17 GB**.

At that point, raw SQL queries become very expensive.

For example:

* `COUNT(*)` on a table with **12 million rows** took around **50 seconds**
* the largest table contains up to **98 million rows**
* naïve `fetchAll()` approaches easily lead to:

```text
Allowed memory size of 134217728 bytes exhausted
```

In other words: **out of memory** and unusable response times.

Without optimization, the project cannot work efficiently.

---

## Importing Data

Loading TSV data directly through `psql` was not as convenient as expected, because TSV handling is less friendly in some typical workflows.

The solution was simple:

* import data through the console
* prepare the schema in advance
* load the raw IMDb datasets into PostgreSQL tables

Once the data is loaded, the real optimization work begins.

---

## Optimization Strategy

### 1. Add indexes

The first major step was to create indexes on frequently queried columns.

That includes:

* regular indexes on searchable fields
* foreign-key related indexes for joins
* text search indexes for `LIKE` queries

For PostgreSQL trigram search, this was especially important:

```sql
USING gin (lower(original_title) gin_trgm_ops)
```

This is a PostgreSQL-specific optimization and makes partial text search much faster.

---

### 2. Avoid loading everything at once

Even after indexing, returning all rows is still not realistic.

The solution is **pagination**.

Instead of trying to fetch everything, the backend returns results in smaller chunks, for example:

* **100 rows per request**

This immediately makes the application usable.

Result:

* database query
* server-side processing
* response delivery
* rendering on the client

all together can take around **400 ms**, even when the source table contains **12 million rows**.

That is already a very solid result for a large dataset.

---

### 3. Optimize for read-heavy workloads

This project is focused mainly on **read operations**.

Write operations are not the main concern here and can later be handled through **queues** if needed.

That makes it reasonable to optimize aggressively for reads.

---

### 4. Add caching in Symfony

Even with good query performance, hitting the database on every request is still unnecessary overhead.

Symfony provides a very convenient caching layer.

Inside the repository methods, results can be cached based on function parameters.
That means repeated calls with the same arguments can return data directly from cache instead of querying PostgreSQL again.

This brought response times down even further.

### Result with cache

* repeated request time dropped to around **220 ms**

At that point, from the user’s perspective, it no longer matters much whether the source contains:

* 10 rows
* 12 million rows

The experience already feels fast.

---

## Key Takeaways

Working with large relational datasets is not only about storing the data.
It is about making access to that data practical.

This project shows a simple but effective path:

1. import the raw dataset
2. structure tables correctly
3. add the right indexes
4. optimize text search with PostgreSQL-specific tools
5. avoid full reads
6. paginate results
7. cache repeated queries

None of these steps are exotic, but together they make a huge difference.

---

## Tech Stack

* **PHP**
* **Symfony**
* **PostgreSQL**

---

## Purpose of the Project

This repository exists to experiment with:

* large database imports
* PostgreSQL indexing strategies
* fast search on millions of rows
* pagination techniques
* repository-level caching in Symfony
* practical performance tuning

The goal is not to build a beautiful UI.
The goal is to understand how to make large datasets searchable and responsive with a relatively simple stack.

---

## Final Thoughts

This is not a production-grade IMDb clone.

It is a technical experiment focused on one practical engineering challenge:

**How do you make search fast when your database is already big enough to hurt?**

And the answer turns out to be quite straightforward:

* use the database correctly
* index what matters
* fetch less
* cache repeated work

That is already enough to turn a slow and memory-hungry prototype into something that feels fast.

---

## License / Data Source

This project uses the **IMDb Non-Commercial Datasets**.
Please review IMDb’s own terms and licensing conditions for dataset usage.
