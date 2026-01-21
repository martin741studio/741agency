---
name: generating-leads
description: Generates leads for hospitality businesses in Bali by scraping Instagram and Google Maps. Use when the user asks to find new clients, scrape leads, or populate a contact list for outreach.
---

# Generating Leads

## When to use this skill
- User asks to "scrape leads" or "find contacts".
- User wants a list of Instagram or Google Maps accounts for outreach.
- Specifically targeted for **Bali Hospitality** (but scripts can be modified).

## Workflow
1.  **Check Dependencies**: Ensure `instaloader`, `playwright`, `typer` are installed.
2.  **Run Scraper**: Execute the python script to fetch leads.
3.  **Return Result**: Provide the path to the generated markdown checklist.

## Instructions

To generate a new list of leads, run the `task_builder.py` script located in this skill's `scripts` folder.

```bash
# Install dependencies if not present (check first)
pip install -r .agent/skills/generating-leads/scripts/requirements.txt
playwright install

# Run the lead generator
# Default: 20 IG leads, 20 G-Maps leads
python .agent/skills/generating-leads/scripts/task_builder.py generate-leads
```

### Options
You can customize the number of leads:
```bash
python .agent/skills/generating-leads/scripts/task_builder.py generate-leads --ig-limit 10 --gm-limit 10
```

## Resources
- [Scraper Script](scripts/scraper.py)
- [Task Builder](scripts/task_builder.py)
