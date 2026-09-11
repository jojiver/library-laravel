# Setup on a New PC

Steps to get the full app (code + database + uploaded book images) running
after cloning this repository on a different computer.

## What is committed vs. what you must restore

| Item            | How it transfers                    |
|-----------------|-------------------------------------|
| Source code     | via git (committed)                 |
| Book images     | via git — `storage/app/public/books` was un-ignored |
| **Database**    | **NOT in git** — restore from `library_database.sql` |
| `.env` config   | **NOT in git** — create from `.env.example` |

## On the new PC

1. Prerequisites: install Docker Desktop and Git, then open a terminal
   (PowerShell) in a folder of your choice.

2. Clone the repository and enter it:

   ```powershell
   git clone https://github.com/jojiver/library-laravel.git
   cd library-laravel
   ```

3. Create your local config (the project's `.env` is not tracked because it
   holds secrets). Copy the example and make sure the database settings match
   the ones in `docker-compose.yml` (`library` / `library` / `librarysecret`):

   ```powershell
   copy .env.example .env
   ```

   `.env` must contain at minimum:
   - `APP_KEY=`  (generate it in step 5)
   - `DB_DATABASE=library`, `DB_USERNAME=library`, `DB_PASSWORD=librarysecret`

4. Start the containers (MySQL + PHP-FPM + Nginx). The first build takes a
   few minutes:

   ```powershell
   docker compose up -d
   ```

5. Finish setup inside the API container:

   ```powershell
   docker compose exec library-api php artisan key:generate
   docker compose exec library-api php artisan storage:link
   ```

6. Restore your data from the dump. This creates all tables and imports all
   book/borrowing/user records:

   ```powershell
   cmd /c "docker exec -i library-db mysql -u library -plibrarysecret library < library_database.sql"
   ```

   (PowerShell cannot use `<` for input redirection, so the command is wrapped
   in `cmd /c`.)

7. Verify:
   - API: open `http://localhost:8000/api/books` — you should see your books.
   - Book images: open a book detail page in the Laravel API app.
     Image URLs are `/storage/books/...`; if they 404, re-run `storage:link`.
   - phpMyAdmin: `http://localhost:8081` if that service is enabled.

## Updating your dump after making changes

Whenever you add/change/delete records, re-create the dump so the next PC
gets the latest data:

```powershell
docker exec library-db sh -c "mysqldump -u library -plibrarysecret --add-drop-table library > /tmp/library_database.sql"
docker cp library-db:/tmp/library_database.sql library_database.sql
```

Then commit and push `library_database.sql` along with your changes.

## Note

- The database password appears in the commands only to keep local setup
  simple. Do not publish a `.env` containing real credentials.