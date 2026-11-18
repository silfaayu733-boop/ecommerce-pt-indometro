# Deployment Guide – Laravel E‑Commerce (Vercel + Railway MySQL)

## 1️⃣ Prerequisites
- Git installed locally.
- Composer installed.
- PHP 8.2 (or compatible) installed.
- Access to a **Railway** account with a MySQL plugin.
- A **GitHub** account (repo will be private/public as you prefer).
- A **Vercel** account.

## 2️⃣ Prepare environment variables (place‑holders only)
Edit the file `.env.example` – **DO NOT** add any real passwords. Use the placeholders below:
```
APP_NAME=Laravel
APP_ENV=production
APP_KEY=                 # generated later with `php artisan key:generate --show`
APP_DEBUG=false
APP_URL=https://YOUR_VERCEL_URL.vercel.app

DB_CONNECTION=mysql
DB_HOST=YOUR_RAILWAY_MYSQL_HOST
DB_PORT=3306
DB_DATABASE=YOUR_RAILWAY_DB_NAME
DB_USERNAME=YOUR_RAILWAY_USERNAME
DB_PASSWORD=YOUR_RAILWAY_PASSWORD

FILESYSTEM_DISK=local   # using local storage (public/uploads) on Vercel
```
All other variables already in the file can stay with their default values.

## 3️⃣ Generate the application key (run locally)
```powershell
php artisan key:generate --show
```
Copy the generated string (e.g. `base64:xxxxxxxx`) and replace the empty `APP_KEY=` line in `.env.example`.

## 4️⃣ Create a GitHub repository
1. Go to https://github.com and create a new repository (e.g. `pt-indometro`).
2. Do **NOT** add any `.env` files – they are already ignored by `.gitignore`.
3. Copy the remote URL (HTTPS) – you will need it in step 5.

## 5️⃣ Push the project to GitHub (run in project root)
```powershell
cd d:\project_skripsi_perancangan_e-commerce_bahan_bangunan_PT_indometro

git init                 # if the repository is not already a git repo

git add .                # .gitignore will skip .env, vendor, node_modules, backup, etc.

git commit -m "Initial commit – Laravel e‑commerce (no secrets)"

git remote add origin https://github.com/your‑user/pt-indometro.git

git push -u origin master   # you will be asked for GitHub username & PAT (personal access token)
```
> **Important:** If you are using two‑factor authentication, generate a PAT with `repo` scope.

## 6️⃣ Connect the repository to Vercel
1. Log in to https://vercel.com.
2. Click **New Project** → **Import Git Repository** → select the GitHub repository you just pushed.
3. Vercel will detect the `vercel.json` file and automatically set the builder to `@vercel/php`.
4. Click **Continue** → **Deploy** (you can skip environment variables for now; they will be added in the next step).

## 7️⃣ Set environment variables in Vercel (manual step)
Open **Settings → Environment Variables** of the newly created Vercel project and add the following keys **with the real values** from Railway and the generated `APP_KEY`:
| Variable | Example value |
|----------|---------------|
| `APP_NAME` | `PT. Indo Metro` |
| `APP_ENV` | `production` |
| `APP_KEY` | `base64:xxxxxxxxxxxx` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://your‑project.vercel.app` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `containers-us-west-123.railway.app` |
| `DB_PORT` | `3306` |
| `DB_DATABASE` | `mydatabase` |
| `DB_USERNAME` | `postgres` |
| `DB_PASSWORD` | `superSecretPassword` |
| `FILESYSTEM_DISK` | `local` |
| `SESSION_DRIVER` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `CACHE_STORE` | `database` |
| (optional) `MAIL_FROM_ADDRESS` | `hello@example.com` |
| (optional) `MAIL_FROM_NAME` | `PT. Indo Metro` |
> **Do NOT** add any `.env` file to the repo. All values are stored securely inside Vercel.

## 8️⃣ Trigger a new deployment
After saving the environment variables, Vercel will automatically start a new build. You can also click **Redeploy** manually.

### What Vercel does:
1. **Clones** the GitHub repo.
2. Runs `composer install --prefer-dist --no-dev`.
3. Executes `php artisan migrate` – this will create the tables in the Railway MySQL database using the credentials you supplied.
4. Serves the application via the `api/index.php` entry point.

## 9️⃣ Verify the deployment
- Open the URL shown in the Vercel dashboard (e.g. `https://your‑project.vercel.app`).
- Test the following flows:
  - Home page loads.
  - User registration / login works.
  - Product list and images (stored under `public/uploads`) are visible.
  - Checkout process creates an invoice.
  - Uploading a payment proof stores the file under `storage/app/public` and can be viewed (only by the owner).
- If any step fails, open **Deployments → View Build Output** in Vercel, copy the error log and share it with me.

## 🔧 Troubleshooting tips (without losing data)
| Symptom | Likely cause | Fix |
|----------|--------------|-----|
| `SQLSTATE[HY000] [2002] Connection refused` | Wrong Railway host/port or DB not reachable | Double‑check `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` in Vercel env vars. |
| `Class 'App\Models\User' not found` | Composer autoload out‑of‑date | Ensure `composer install` runs without errors; you can add a **post‑install** script in `composer.json` → `php artisan optimize`.
| Uploaded images disappear after redeploy | Vercel filesystem is ephemeral | For production you should store uploads in a persistent service (S3, R2, etc.). For a demo you can keep them local, but avoid frequent redeploys. |
| `php artisan storage:link` fails | `public/storage` symlink cannot be created on read‑only Vercel | Vercel automatically serves files from `public/` – make sure your code uses `asset('storage/...')` which resolves to the same path. |

## 📦 What you still need to provide
- **Railway MySQL credentials** (host, database, username, password) – paste them into Vercel env vars (step 7).
- **Generated APP_KEY** – paste into Vercel env vars (step 7).

Once those values are set, Vercel will finish the build and you should have a live Laravel e‑commerce site accessible at the Vercel URL.

---
*This guide was generated by Antigravity. All commands are Windows‑compatible (PowerShell).*