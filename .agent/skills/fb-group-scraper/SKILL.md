---
name: FB Group Scraper
description: Scrape Facebook groups for posts containing specific keywords using a persistent Playwright browser context.
---

# FB Group Scraper Skill

This skill allows you to scrape public Facebook groups for posts that match specific keywords. It uses Playwright with a persistent browser context to maintain login sessions, avoiding the need for repeated logins and reducing the risk of being flagged.

## Setup

1.  **Install Dependencies**: Navigate to the `scripts` directory and install the necessary packages.
    ```bash
    cd .agent/skills/fb-group-scraper/scripts
    npm install
    ```

2.  **Initial Login**: To use this scraper, you must first log in to Facebook manually using the persistent browser context. The script uses a directory named `fb-profile` to store your session.
    Run the script once to open the browser, log in to Facebook, and then close the browser manually or let the script finish (it might fail to find elements if not logged in, but the session will be saved).
    *Tip: You can modify the script temporarily to just open the browser and wait if you need more time to log in.*

## Configuration

The scraping behavior is controlled by `config.json` located in the `scripts` directory.

```json
{
  "groups": [
    "https://www.facebook.com/groups/EXAMPLE_GROUP_ID"
  ],
  "include_keywords": [
    "keyword1", "keyword2"
  ],
  "exclude_keywords": ["exclude1", "exclude2"]
}
```

-   `groups`: An array of Facebook group URLs to scrape.
-   `include_keywords`: Posts containing *any* of these keywords (case-insensitive) will be saved.
-   `exclude_keywords`: Posts containing *any* of these keywords will be skipped, even if they match an include keyword.

## Usage

To run the scraper:

```bash
cd .agent/skills/fb-group-scraper/scripts
node fb_job_radar.js
```

The script will:
1.  Launch a browser (headless: false by default for visibility and lower detection risk).
2.  Navigate to each group URL.
3.  Scroll down to load recent posts.
4.  Extract post links.
5.  Visit each post link to extract the content.
6.  Filter posts based on your keywords.
7.  Save matching posts to `out.json` in the same directory.

## Output

The results are saved to:
1.  `out.json` (JSON format)
2.  `out.csv` (CSV format, can be opened in Excel)

```json
[
  {
    "groupUrl": "https://www.facebook.com/groups/...",
    "postUrl": "https://www.facebook.com/groups/.../posts/...",
    "snippet": "Post content snippet...",
    "foundAt": "2023-10-27T10:00:00.000Z"
  }
]
```

