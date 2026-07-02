# PDrive

PDrive is a Laravel-based personal cloud storage application inspired by Google Drive. It lets each authenticated user create nested folders, upload files, preview supported content, and manage items with actions like rename, move, copy, favorite, download, trash, restore, and permanent delete.

The app is built with Laravel 13, Breeze authentication, Tailwind CSS, Alpine.js, and Vite.

## Features

- User registration, login, password reset, email verification, and profile management
- Personal file space scoped per authenticated user
- Nested folder navigation with breadcrumbs
- File upload into the root or any folder
- File preview support for:
  - Images
  - PDFs
  - Audio
  - Video
  - Text/code files
- Grid-based file browsing with image thumbnails
- Full-screen image preview from the drive grid
- File actions:
  - Open
  - Download
  - Rename
  - Move
  - Copy
  - Favorite / unfavorite
  - Trash
- Folder actions:
  - Open
  - Rename
  - Move
  - Copy
  - Favorite / unfavorite
  - Trash
- Favorites screen for quick access
- Trash screen with restore and permanent delete
- Storage usage stats on the dashboard
- File content served through Laravel routes so previews work without depending on direct public file URLs

## Tech Stack

- PHP 8.3
- Laravel 13
- Laravel Breeze
- MySQL 8
- Tailwind CSS
- Alpine.js
- Vite
- Lando for local containerized development

## Project Structure

- `app/Http/Controllers/DashboardController.php`
  Handles the main drive dashboard, folder navigation, favorites, trash, breadcrumbs, and storage statistics.
- `app/Http/Controllers/FileController.php`
  Handles file upload, preview, inline content serving, download, rename, move, copy, favorite, trash, restore, and permanent delete.
- `app/Http/Controllers/FolderController.php`
  Handles folder creation and recursive folder operations such as move, copy, trash, restore, and permanent delete.
- `app/Models/DriveFile.php`
  File model with preview helpers and display helpers such as human-readable size.
- `app/Models/Folder.php`
  Folder model with parent/child relationships.
- `resources/views/drive/`
  Blade views for the main drive UI, favorites, trash, and file preview.

## Requirements

- PHP 8.3+
- Composer
- Node.js and npm
- MySQL or another Laravel-supported database

Optional:

- Lando, if you want to run the project inside the provided local container setup

## Environment Setup

1. Clone the repository.
2. Install PHP dependencies.
3. Install frontend dependencies.
4. Create the environment file.
5. Generate the application key.
6. Configure the database in `.env`.
7. Run migrations.
8. Create the storage symlink if you want the standard Laravel public storage path available.

Example:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
```

## Quick Start

### Option 1: Standard Laravel local development

Run the backend server:

```bash
php artisan serve
```

Run Vite in another terminal:

```bash
npm run dev
```

Open the app in your browser using the URL shown by `php artisan serve`.

### Option 2: Composer helper script

The project includes a `composer dev` script that starts:

- Laravel development server
- Queue listener
- Laravel Pail log viewer
- Vite dev server

```bash
composer dev
```

### Option 3: Lando

This repository includes a `.lando.yml` file configured for:

- PHP 8.3
- MySQL 8.0
- phpMyAdmin

If you use Lando, start the environment and then run Laravel setup commands inside it:

```bash
lando start
lando php artisan key:generate
lando php artisan migrate
lando php artisan storage:link
```

The local project URL in this setup may be similar to:

```text
https://pdrive.lndo.site
```

Use `lando info` to confirm the exact local URLs on your machine.

## Default App Flow

1. Visit `/`
2. Unauthenticated users are redirected to login
3. Authenticated users are redirected to `/dashboard`
4. From the dashboard users can:
   - Create folders
   - Upload files
   - Open folders
   - Open files
   - Search within the current view
   - Access favorites and trash

## File and Folder Behavior

### Folder behavior

- Folders belong to a single user
- Folders can be nested with `parent_id`
- Folder move prevents moving a folder into itself or one of its descendants
- Folder copy duplicates child folders and files recursively
- Folder trash recursively soft-deletes nested content
- Folder restore recursively restores nested content
- Permanent delete removes folders and the associated physical files from storage

### File behavior

- Files belong to a single user
- Files may optionally belong to a folder
- Uploaded files keep their original name for display
- Files are stored using a generated internal filename to avoid collisions
- Downloads return the original filename
- Copying a file duplicates the stored file on disk and creates a new database record
- Soft delete is used for trash support

## Storage and Preview Notes

Uploaded files are stored on the `public` filesystem disk under:

```text
storage/app/public/uploads
```

Even though the app supports the usual Laravel `storage:link` flow, file previews in the UI are served through the authenticated route:

```text
/files/{file}/content
```

This is useful because:

- file access remains user-scoped
- previews do not rely only on public direct links
- images can render inside the drive grid
- downloads preserve the original filename

Supported preview types currently include:

- Image previews and thumbnails
- PDF preview page
- Audio preview
- Video preview
- Text preview for common text and code formats

## Database

Key tables:

- `users`
- `folders`
- `drive_files`

### `folders`

Important fields:

- `user_id`
- `parent_id`
- `name`
- `is_favorite`
- `deleted_at`

### `drive_files`

Important fields:

- `user_id`
- `folder_id`
- `original_name`
- `stored_name`
- `disk`
- `path`
- `mime_type`
- `extension`
- `size`
- `is_favorite`
- `deleted_at`

## Main Routes

Authenticated routes include:

- `GET /dashboard`
- `GET /folders/{folder}`
- `GET /favorites`
- `GET /trash`

Folder actions:

- `POST /folders`
- `PATCH /folders/{folder}`
- `PATCH /folders/{folder}/favorite`
- `PATCH /folders/{folder}/move`
- `POST /folders/{folder}/copy`
- `DELETE /folders/{folder}`
- `POST /trash/folders/{folder}/restore`
- `DELETE /trash/folders/{folder}/force`

File actions:

- `POST /files`
- `GET /files/{file}`
- `GET /files/{file}/content`
- `GET /files/{file}/download`
- `PATCH /files/{file}`
- `PATCH /files/{file}/favorite`
- `PATCH /files/{file}/move`
- `POST /files/{file}/copy`
- `DELETE /files/{file}`
- `POST /trash/files/{file}/restore`
- `DELETE /trash/files/{file}/force`

## Frontend Notes

The UI is rendered with Blade templates and styled with Tailwind CSS. Alpine.js is used for lightweight interactions such as:

- image viewer overlays
- file action menus
- in-card action panels

Important view files:

- `resources/views/drive/index.blade.php`
- `resources/views/drive/preview.blade.php`
- `resources/views/drive/favorites.blade.php`
- `resources/views/drive/trash.blade.php`

## Development Commands

Install dependencies:

```bash
composer install
npm install
```

Run migrations:

```bash
php artisan migrate
```

Generate app key:

```bash
php artisan key:generate
```

Create storage link:

```bash
php artisan storage:link
```

Run the app locally:

```bash
php artisan serve
npm run dev
```

Run tests:

```bash
composer test
```

Build frontend assets for production:

```bash
npm run build
```

## Testing

The repository includes Laravel feature and unit tests under:

- `tests/Feature`
- `tests/Unit`

Run the test suite with:

```bash
composer test
```

## Security Notes

- User access to files and folders is checked in controllers before operations are performed
- File preview and download routes are authenticated
- Do not commit your real `.env` file
- Review any public deployment configuration before exposing the app on the internet

## Known Limitations

- This is a personal-drive style application, not a multi-user sharing platform
- Folder and file permissions are user-scoped only
- No collaborative sharing, roles, or public links are implemented yet
- Large-file handling, resumable uploads, and background processing are not implemented yet

## Possible Next Improvements

- Shareable links
- Multi-select actions
- Drag and drop uploads
- Grid/list view toggle
- Better PDF and document previews
- File type icons for more formats
- Pagination or lazy loading for large drives
- Quota enforcement per user

## License

This project is open source and available under the [MIT License](LICENSE).
