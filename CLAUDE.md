# Natrx Website — Project Context

## Stack
- WordPress on Cloudways (DigitalOcean NYC3, server `1587554`, app `6366727`)
- Theme: Semplice v6 (active), Semplice v5 (legacy, keep)
- Server IP: `164.90.140.41`, SSH user: `dylandibona`, app sys user: `yetjkkygea`
- WordPress path: `/home/master/applications/yetjkkygea/public_html/`
- GitHub: `git@github.com:dylan-natrx/website2026.git`

## Repo Structure
```
/
├── themes/
│   ├── semplice5/   # legacy, tracked for reference
│   └── semplice6/   # active theme
├── deploy.sh        # rsync to Cloudways production
└── .gitignore
```

## Deploy Workflow
1. Edit theme files in `themes/semplice6/`
2. `git add . && git commit -m "..."` then `git push`
3. `./deploy.sh semplice6` — rsyncs to production over SSH

> Note: This directory is inside Google Drive (Insync). Avoid heavy git operations while Insync is actively syncing.

## Completed
- [x] Git repo initialized, `.gitignore` configured, pushed to GitHub
- [x] `deploy.sh` created (rsync over SSH) — **not yet tested end-to-end**
- [x] `natrx.io` set as primary domain on Cloudways (was cloudwaysapps.com subdomain)
- [x] SSL (Let's Encrypt) active through July 2026, HTTPS redirection enabled
- [x] Redis Object Cache Pro activated and connected (58MB cached)
- [x] Breeze cache configured: page cache, Gzip, browser cache, lazy load images on; file optimization (CSS/JS minify) intentionally left OFF — breaks Semplice
- [x] Yoast SEO reviewed: 0 technical problems; meta titles/descriptions updated on key pages

## Still To Do
- [ ] **Test deploy.sh** — run once on a harmless change to verify rsync path is correct
- [ ] **Image optimization** — install ShortPixel or Imagify; run bulk compress on existing uploads (biggest remaining performance gap)
- [ ] **Google Search Console** — verify ownership and submit sitemap at `natrx.io/sitemap_index.xml`
- [ ] **Yoast: Portfolio content type** — go to Yoast → Settings → Search Appearance → configure the Semplice Portfolio post type to be indexed with a proper title template
- [ ] **30 unanalyzed pages** — each needs a focus keyphrase + meta description set in Yoast (open in editor → Yoast panel)
- [ ] **Local dev setup** — connect Local (by Flywheel) to Cloudways site for proper edit → test → deploy workflow
- [ ] **1 plugin update pending** — check WP Admin → Plugins for what needs updating
- [ ] **Wildcard SSL** — current cert covers `natrx.io` and `www.natrx.io` only; if any subdomains point to this server (164.90.140.41) they need to be added to the cert

## Key Notes
- Do NOT enable CSS/JS minify or Combine JS in Breeze — breaks Semplice layouts
- Do NOT use Gutenberg or type in the WP content area on Semplice pages — edit Yoast fields only via the Yoast panel in the Semplice editor or WP backend
- Yoast SEO fields are safe to edit independently of Semplice data
- Redis credentials are in `wp-config.php` (do not commit changes to this file)
- GitHub active account must be `dylan-natrx` to push (use `gh auth switch --user dylan-natrx`)
