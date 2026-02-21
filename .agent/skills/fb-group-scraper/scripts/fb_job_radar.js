import { chromium } from "playwright";
import fs from "fs";
import readline from 'readline';

const CONFIG = JSON.parse(fs.readFileSync("./config.json", "utf8"));

function matches(text) {
    const t = (text || "").toLowerCase();
    if (CONFIG.exclude_keywords.some(k => t.includes(k.toLowerCase()))) return false;
    return CONFIG.include_keywords.some(k => t.includes(k.toLowerCase()));
}

(async () => {
    console.log("Launching browser...");
    // Use a persistent profile so you log in once manually
    const context = await chromium.launchPersistentContext("./fb-profile", {
        headless: process.env.FB_HEADLESS === 'true',
        viewport: { width: 1280, height: 800 } // Setting a specific viewport size
    });
    const page = await context.newPage();

    // Navigate to Facebook home first
    console.log("Navigating to Facebook...");
    try {
        await page.goto("https://www.facebook.com", { waitUntil: "domcontentloaded" });
    } catch (e) {
        console.log("Initial navigation error (might be offline or network issue):", e.message);
    }

    // --- MANUAL LOGIN CHECK ---
    if (process.env.FB_NON_INTERACTIVE !== 'true') {
        console.log("\n==================================================");
        console.log(" MANUAL LOGIN REQUIRED");
        console.log("==================================================");
        console.log("1. A browser window should have opened.");
        console.log("2. Please log in to your Facebook account in that window.");
        console.log("3. If you are already logged in, just wait on the home page.");
        console.log("4. Once you are successfully logged in and can see your feed, come back here.");
        console.log("5. PRESS ENTER to start scraping.");
        console.log("==================================================\n");

        const rl = readline.createInterface({
            input: process.stdin,
            output: process.stdout
        });

        await new Promise(resolve => {
            rl.question('Press ENTER when you are logged in and ready...', () => {
                rl.close();
                resolve();
            });
        });
    } else {
        console.log("[Auto] Skipping manual login prompt (assuming persistent context is valid).");
        await page.waitForTimeout(5000); // Give it a sec to settle
    }

    console.log("\nStarting scraper...");

    const results = [];

    for (const groupUrl of CONFIG.groups) {
        try {
            console.log(`Navigating to group: ${groupUrl}`);
            await page.goto(groupUrl, { waitUntil: "domcontentloaded" });
            await page.waitForTimeout(3000);

            // Light scroll to load recent posts
            for (let i = 0; i < 6; i++) {
                await page.mouse.wheel(0, 1200);
                await page.waitForTimeout(1500);
            }

            // DEBUG: Log all links to see what we are getting
            const allLinks = await page.$$eval('a', as => as.map(a => a.href));
            console.log(`Debug: Found ${allLinks.length} total links on page.`);
            if (allLinks.length > 0) {
                console.log("Debug: First 5 links found:", allLinks.slice(0, 5));
            }

            // Collect candidate post links (FB markup changes often; adjust selectors as needed)
            // Trying multiple selectors to be more robust
            const postLinks = await page.$$eval('a', as =>
                Array.from(new Set(as.map(a => a.href)))
                    .filter(href => href.includes('/posts/') || href.includes('/permalink/'))
                    .slice(0, 50)
            );

            console.log(`Found ${postLinks.length} potential post links in group.`);

            for (const url of postLinks) {
                try {
                    // Ensure URL is absolute and clean
                    const fullUrl = url.startsWith('http') ? url : `https://www.facebook.com${url}`;

                    await page.goto(fullUrl, { waitUntil: "domcontentloaded" });
                    await page.waitForTimeout(2000);

                    const text = await page.textContent("body");
                    if (!matches(text)) continue;

                    const snippet = (text || "").replace(/\s+/g, " ").slice(0, 500);
                    console.log(`Found match: ${snippet.substring(0, 50)}...`);

                    results.push({
                        groupUrl,
                        postUrl: fullUrl,
                        snippet: snippet,
                        foundAt: new Date().toISOString(),
                    });
                } catch (e) {
                    console.error(`Error processing post ${fullUrl}:`, e.message);
                }
            }
        } catch (e) {
            console.error(`Error processing group ${groupUrl}:`, e.message);
        }
    }

    console.log(`Scraping complete. Found ${results.length} matches.`);

    // Save to JSON
    fs.writeFileSync("./out.json", JSON.stringify(results, null, 2));

    // Save to CSV
    if (results.length > 0) {
        const csvHeader = "Group URL,Post URL,Snippet,Found At\n";
        const csvRows = results.map(r => {
            const snippetSafe = r.snippet.replace(/"/g, '""'); // Escape quotes
            return `"${r.groupUrl}","${r.postUrl}","${snippetSafe}","${r.foundAt}"`;
        }).join("\n");
        fs.writeFileSync("./out.csv", csvHeader + csvRows);
        console.log("Results saved to out.json and out.csv");
    } else {
        console.log("No matching posts found. creating empty files.");
        fs.writeFileSync("./out.csv", "Group URL,Post URL,Snippet,Found At\n");
    }

    await context.close();
})();
