# Library Management System API

A Laravel 12 API for a library management system, built with a strict **layered architecture**:

**Controller → Form Request → Service → Repository Interface → Repository → Eloquent Model**

The API is consumed by the React frontend in [`library-ui-react`](../library-ui-react) (base URL `http://localhost:8000/api`) and runs entirely inside Docker (nginx + PHP-FPM + MySQL).

---

## Table of Contents

- [Installation](#installation)
- [API Documentation](#api-documentation)
- [Architecture Explanation](#architecture-explanation)
- [Data Flow: POST /api/borrowings](#data-flow-post-apiborrowings)
- [Final Question](#final-question)
- [Tests](#tests)

---

## Installation

### Requirements

- Docker Desktop (Docker Compose)
- The API listens on `http://localhost:8000`

### Steps

1. **Clone / open the project**

   ```bash
   cd library-api
   ```

2. **Build and start the Docker containers**

   ```bash
   docker compose up -d --build
   ```

   This starts three services:

   | Service | Container | Purpose                              |
   |---------|-----------|--------------------------------------|
   | `web`   | nginx     | Serves Laravel on host port `8000`   |
   | `app`   | php-fpm   | Runs PHP 8.3 + Composer + extensions |
   | `db`    | MySQL 8   | Persists data (volume `dbdata`)      |

3. **Install dependencies**

   Dependencies are installed inside the image, but re-install them to be safe:

   ```bash
   docker compose exec app php composer.phar install
   ```

4. **Configure `.env`**

   The repository ships with a working `.env.example`. Copy it if needed:

   ```bash
   copy .env.example .env
   ```

   The database settings are already wired for Docker:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=library
   DB_USERNAME=library
   DB_PASSWORD=librarysecret
   ```

5. **Create the database**

   The `db` container creates the `library` database automatically, but you can also create it explicitly:

   ```bash
   docker compose exec db mysql -u root -plibrarysecret -e "CREATE DATABASE IF NOT EXISTS library"
   ```

6. **Run migrations and seeders**

   ```bash
   docker compose exec app php artisan migrate --seed
   ```

   The seeder creates an admin user and sample books:

   | Email             | Password   |
   |-------------------|------------|
   | `admin@library.com` | `password` |

7. **Run the tests**

   Tests use an in-memory SQLite database, so they need no running MySQL:

   ```bash
   docker compose exec app php artisan test
   ```

   (They can also be run on a host machine with PHP 8.2+ and `composer install`.)

8. **Start the Laravel application**

   The application is already running once the containers are up:

   ```bash
   docker compose up -d
   ```

   Health check: `http://localhost:8000/up` — API base: `http://localhost:8000/api`.

---

## API Documentation

All endpoints are prefixed with `/api`. Every JSON body must be sent with the header `Accept: application/json`.

### 1. User Login

**Method / URL:** `POST /api/login`

**Purpose:** Authenticate a borrower and return a Sanctum token + user. (Reader/writer dashboard login.)

**Request body:**

| Field      | Type     | Validation rules |
|------------|----------|------------------|
| `email`    | `string` | required, email |
| `password` | `string` | required |

**Example response — `200 OK`:**

```json
{
  "token": "1|abcdef1234567890...",
  "user": {
    "id": 2,
    "name": "Test User",
    "email": "user@library.com"
  }
}
```

**Possible errors:**

| Status | Meaning |
|--------|---------|
| `422` | Validation failure (`{"message": "The email field is required.", "errors": {...}}`) |
| `401` | Invalid credentials (`{"message": "Invalid credentials."}`) |

### 2. Admin Login

**Method / URL:** `POST /api/admin/login`

**Purpose:** Authenticate the librarian and return a Sanctum token + user.

**Request body:**

| Field      | Type     | Validation rules |
|------------|----------|------------------|
| `email`    | `string` | required, email |
| `password` | `string` | required |

**Example response — `200 OK`:**

```json
{
  "token": "1|abcdef1234567890...",
  "user": {
    "id": 1,
    "name": "Library Admin",
    "email": "admin@library.com"
  }
}
```

**Possible errors:**

| Status | Meaning |
|--------|---------|
| `422` | Validation failure (`{"message": "The email field is required.", "errors": {...}}`) |
| `401` | Invalid credential stripes (`{"message": "Invalid credentials."}`) |

> Both login endpoints share the same request/response shape and the same `AuthService`. For this assessment the public endpoints (books, borrowings, statistics) are intentionally open. The token returned here can be passed as `Authorization: Bearer <token>` for future protecting.

### 3. List Books

**Method / URL:** `GET /api/books`

**Purpose:** Return a paginated, searchable and filterable list of books.

**Query parameters:**

| Parameter | Type | Validation / notes |
|-----------|------|---------------------|
| `page` | `integer` | optional, default `1` |
| `per_page` | `integer` | optional, default `15` |
| `search` | `string` | optional; matches title, author, or ISBN |
| `category` | `string` | optional; exact match |
| `status` | `string` | optional; `available` or `unavailable` |
| `author` | `string` | optional; exact match |

**Example response — `200 OK`:**

```json
{
  "data": [
    {
      "id": "uuid-...",
      "title": "Clean Code",
      "author": "Robert C. Martin",
      "isbn": "9780132350884",
      "category": "Programming",
      "published_year": 2008,
      "quantity": 5,
      "available_quantity": 3,
      "status": "available"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 2,
    "total": 18,
    "per_page": 15
  },
  "links": {}
}
```

### 4. Create Book

**Method / URL:** `POST /api/books`

**Purpose:** Add a new book to the library.

**Request body:**

| Field | Type | Validation rules |
|-------|------|------------------|
| `title` | `string` | required, string, max 255 |
| `author` | `string` | required, string, max 150 |
| `isbn` | `string` | required, string, max 50, unique |
| `category` | `string` | required, string, max 100 |
| `published_year` | `integer` | required, integer, `1900..current year` |
| `quantity` | `integer` | required, integer, min `1` |
| `available_quantity` | `integer` | required, integer, min `0`, `<= quantity` |
| `status` | `string` | required, `in:available,unavailable` |

**Example response — `201 Created`:** same `data` shape as `GET /api/books` (list item).

**Possible errors:** `422` validation failure; `409` not applicable for creation.

### 5. Show Book

**Method / URL:** `GET /api/books/{id}`

**Purpose:** Return a single book by UUID.

**Example response — `200 OK`:** a single `data` object (same shape as above).

**Possible errors:** `404` — `{"message": "Book not found."}`.

### 6. Update Book

**Method / URL:** `PUT /api/books/{id}`

**Purpose:** Update an existing book.

**Request body:** same fields and rules as Create Book, except `isbn` is unique while *ignoring* the book being updated (so a book may keep its own ISBN). `available_quantity` must still be `<= quantity`.

**Example response — `200 OK`:** updated `data` object.

**Possible errors:** `404` book not found; `422` validation failure (including a duplicated ISBN on another book).

### 7. Delete Book

**Method / URL:** `DELETE /api/books/{id}`

**Purpose:** Remove a book. The delete is rejected while the book still has any borrowing with status `borrowed` (protects data integrity); otherwise the book together with its returned borrowing history is removed (cascade delete).

**Example response — `200 OK`:**

```json
{ "message": "Book deleted successfully." }
```

**Possible errors:**

| Status | Meaning |
|--------|---------|
| `404` | `{"message": "Book not found."}` |
| `409` | `{"message": "This book cannot be deleted while it still has active borrowings."}` |

### 8. List Borrowings

**Method / URL:** `GET /api/borrowings`

**Purpose:** Return a paginated, filterable list of borrowings.

**Query parameters:**

| Parameter | Type | Validation / notes |
|-----------|------|---------------------|
| `page` | `integer` | optional, default `1` |
| `per_page` | `integer` | optional, default `15` |
| `status` | `string` | optional; `borrowed` or `returned` |
| `borrower_email` | `string` | optional; exact match |

**Example response — `200 OK`:**

```json
{
  "data": [
    {
      "id": "uuid-...",
      "book_id": "uuid-...",
      "borrower_name": "John Doe",
      "borrower_email": "john@example.com",
      "borrowed_at": "2026-09-10T08:00:00+00:00",
      "returned_at": null,
      "status": "borrowed"
    }
  ],
  "meta": { "current_page": 1, "last_page": 1, "total": 1, "per_page": 15 },
  "links": {}
}
```

### 9. Borrow a Book

**Method / URL:** `POST /api/borrowings`

**Purpose:** Create a borrowing record and decrease the book's availability.

**Request body:**

| Field | Type | Validation rules |
|-------|------|------------------|
| `book_id` | `string` (UUID) | required, `exists:books,id` |
| `borrower_name` | `string` | required, string, max 150 |
| `borrower_email` | `string` | required, email, max 255 |

**Example response — `201 Created`:** a single `data` object where `status` is always `borrowed`, `borrowed_at` is server time, and `returned_at` is `null`. Client-supplied `status`/`borrowed_at` values are ignored.

**Possible errors:**

| Status | Meaning |
|--------|---------|
| `422` | Validation failure (`book_id` must exist, valid email, etc.) |
| `409` | `{"message": "This book is currently unavailable for borrowing."}` when no copies are available |

### 10. Show Borrowing

**Method / URL:** `GET /api/borrowings/{id}`

**Purpose:** Return a single borrowing record by UUID.

**Example response — `200 OK`:** single `data` object.

**Possible errors:** `404` — `{"message": "Borrowing not found."}`.

### 11. Return a Book

**Method / URL:** `PUT /api/borrowings/{id}/return`

**Purpose:** Mark a borrowing as returned and increase the book's availability.

**Example response — `200 OK`:** the borrowing with `status: "returned"` and a non-null `returned_at`.

**Possible errors:**

| Status | Meaning |
|--------|---------|
| `404` | `{"message": "Borrowing not found."}` |
| `409` | `{"message": "This borrowing has already been returned."}` |

### 12. Library Statistics

**Method / URL:** `GET /api/library/statistics`

**Purpose:** Return summary counts used by the dashboard.

**Example response — `200 OK`:**

```json
{
  "total_books": 18,
  "available_books": 12,
  "unavailable_books": 6,
  "total_borrowings": 42,
  "active_borrowings": 5,
  "returned_borrowings": 37
}
```

### Error Shape

All errors come back as JSON:

```json
{ "message": "..." }
```

Validation failures additionally include an `errors` object with per-field messages.

---

## Architecture Explanation

Each folder plays one strict role. The rule used everywhere is: **no layer "jumps" over another**; data always flows through each layer in order.

| Layer | Location | Purpose |
|-------|----------|---------|
| **Controller** | `app/Http/Controllers` | Thin HTTP adapter. Receives the request, delegates to the service, and returns an `API Resource` or JSON. Contains no Eloquent calls and no business rules — tests verify this. |
| **Form Request** | `app/Http/Requests` | Owns **all** validation and authorization of input. Transforms raw input into a clean, validated array via `validated()`. Keeps rules out of controllers and models. |
| **Service** | `app/Services` | Owns the **business logic and use cases** (borrowing rules, availability enforcement, statistics). Uses `DB::transaction` for multi-step writes. Depends only on repository *interfaces*, never on models directly. |
| **Repository Interface** | `app/Repositories/Contracts` | The "contract". Declares the data operations a service may use. Makes the dependency explicit and swap-friendly (e.g. for mocks in tests). |
| **Repository** | `app/Repositories` | The only layer that talks to the database through Eloquent. Implements the interface, builds queries, applies pagination/filtering/search. Bound to its interface in `AppServiceProvider`. |
| **Model** | `app/Models` | Plain Eloquent models: table mapping, fillable attributes, casts, relationships. No query methods, no business logic. |
| **API Resource** | `app/Http/Resources` | Shape the JSON output (which fields are exposed, date formatting). Decouples consumers from the underlying model structure. |

Dependency direction (never the reverse):

```
Controller -> Service -> RepositoryInterface <- Repository -> Eloquent Model
```

(`Form Request` validates input; `API Resource` shapes output.)

In `app/Providers/AppServiceProvider.php` the interfaces are bound to their implementations, so swap logic/tests can substitute mocks without touching any service.

---

## Data Flow: POST /api/borrowings

A step-by-step walk-through of what happens when the React UI submits a borrow:

1. **HTTP request** — The frontend sends `POST /api/borrowings` with `book_id`, `borrower_name` and `borrower_email` to `http://localhost:8000/api`.
2. **Routing** — `routes/api.php` maps the URL to `BorrowingController@store`.
3. **Form Request** — `StoreBorrowingRequest` validates the payload: required fields, valid email, and `book_id` must exist in the `books` table. If a rule fails, a `422` response with `{"message": ..., "errors": {...}}` is returned immediately — the request never reaches the service.
4. **Controller** — `BorrowingController::store` receives the *validated* data (`$request->validated()`) and calls `BorrowingService::borrowBook()`. It contains no database logic.
5. **Service (transaction)** — `borrowBook()` opens a `DB::transaction`:
   - Loads the book through `BookRepositoryInterface::findOrFail()` (a `Book` model instance). If the UUID doesn't exist, a `ModelNotFoundException` is thrown and mapped to `404 {"message": "Book not found."}`.
   - **Rule 1** — `assertAvailable()`: blocks with a `409` `{"message": "This book is currently unavailable for borrowing."}` when `available_quantity <= 0` or `status === 'unavailable'`.
   - **Rule 2** — creates the borrowing via `BorrowingRepositoryInterface::create()` with server-generated `borrowed_at` and `status = 'borrowed'` (client values are never trusted).
   - **Rule 3** — `decreaseAvailability()` updates the book through the repository interface: `available_quantity - 1`, and flips `status` to `unavailable` when it reaches zero.
   - If anything fails inside the transaction, everything rolls back — no borrowing record and no inventory change are ever persisted separately.
6. **Repository → Eloquent** — `BorrowingRepository` performs the actual `Borrowing::create()` / `Book::update()` calls on the models.
7. **Response** — the controller wraps the persisted `Borrowing` model in a `BorrowingResource`, returns it with `201 Created`. The UI reads `response.data.data` and refreshes its counts.

The return flow (`PUT /api/borrowings/{id}/return`) mirrors this: `returnBook()` verifies the borrowing still has `status = 'borrowed'` (else `409 Already returned`), sets `returned_at = now()`, and increments `available_quantity`, restoring `status = 'available'` — all in one transaction.

---

## Final Question

> **Why should a Laravel application separate Controller, Service, Repository, and Model responsibilities instead of placing all logic inside the Controller?**

Putting everything in controllers works for tiny apps but does not scale. Separating responsibilities pays off in five concrete ways:

1. **Separation of responsibilities.** Each layer answers exactly one question. A controller asks "what HTTP result does the user get?", a service asks "what business rule applies here?", a repository asks "how do I get/store this data?", and a model asks "what is this entity?". No class does someone else's job. In this project, an architecture test greps the controllers to guarantee they contain no `Book::`, `Borrowing::`, `User::`, or `DB::` static calls, keeping that contract honest.

2. **Maintainability.** When a business rule changes (e.g. "a book with active borrowings can no longer be deleted"), you change exactly one line in `BookService::deleteBook()` and its test. You don't hunt through controllers. Controllers stay ~20 lines, so a newcomer understands the whole request lifecycle in minutes.

3. **Testability.** Because services depend on repository *interfaces*, a feature test can swap in a Mockery mock and assert the service resolves it. Feature tests (37 of them) exercise the real HTTP stack with an in-memory SQLite database, while the thin layers mean each test targets one behavior. Business rules are tested in isolation, not buried inside a fat controller.

4. **Reusability.** The same service can be called from a web controller, an API controller, a queued job, an artisan command, or a console script without duplicating logic. The borrow service isn't glued to HTTP; it just receives validated data.

5. **Dependency management.** The service container (`AppServiceProvider`) binds `BookRepositoryInterface` → `BookRepository`, etc. The service depends on the *abstraction*, so the concrete implementation can change (SQLite in tests, MySQL in production, or a cache-backed repository later) without editing a single service line.

**How this helps the borrow/return workflow:** the workflow touches two aggregates — the `borrowing` record and the `book` inventory — and must keep them consistent *and* atomic. The layered structure isolates that complexity: `BorrowingService` wraps both updates in one `DB::transaction`, expresses Rules 1–3 in private, named methods (`assertAvailable`, `decreaseAvailability`, `increaseAvailability`), and never talks to the database directly. If a future change makes "return" also notify the borrower by email, only the service grows — the controller, repository, and model stay untouched, and the existing transaction guarantees the data stays consistent.

---

## Tests

```bash
php artisan test
```

39 feature/unit tests, 166 assertions:

- `BookTest` — CRUD, validation, duplicate ISBN (create & update), pagination, search across title/author/ISBN, combined filters, delete protection.
- `BorrowingTest` — borrow/return workflow, availability and status updates, double-return rejection, 404 handling, pagination + filters.
- `StatisticsTest` — dashboard counts.
- `AuthTest` — user & admin login success, invalid credentials, validation.
- `ArchitectureTest` — interface bindings, services depend on interfaces, controllers contain no Eloquent/DB calls, services contain no query-builder calls.

---

## License

Laravel is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).