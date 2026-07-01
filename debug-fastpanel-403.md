[OPEN] Debug session: fastpanel-403

## Symptom
- `https://dayakarya.id` returns `403 Forbidden`
- `https://dayakarya.id/admin` returns `404 Not Found`
- GitHub Actions deploy now succeeds, but runtime web serving is broken

## Initial hypotheses
1. Root proxy entrypoint file such as `index.php` at project root is missing on server after cleanup/pull.
2. Root `.htaccess` rewrite file used by FastPanel is missing on server or not tracked in repo.
3. FastPanel site settings no longer match the deployment layout expected by this repo.
4. Server cleanup removed untracked production-only files that previously made Apache route requests into `public/`.
5. File permissions or ownership on the web root prevent Apache from reading the intended entry files.

## Evidence to collect
- Presence of root `index.php` and root `.htaccess` in repo and on server
- Current contents of `public/.htaccess`
- Current server-side file listing of project root
- Current FastPanel/Apache-facing entrypoint behavior inferred from accessible files

## Status
- Evidence collected: repo lacked root `index.php` and root `.htaccess`, while server runtime required both files plus root asset symlinks to proxy requests into `public/`
- Root cause confirmed: cleanup/deploy removed untracked production-only FastPanel proxy files, causing `/` to return `403` and `/admin` to miss rewrite routing
- Fix in progress: persist proxy files in repo and recreate root asset symlinks during deploy
