import instaloader
from playwright.sync_api import sync_playwright
import time
import random
from typing import List, Dict
import logging

# Configure logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

class LinkedInSearcher:
    def search_contacts(self, company_name: str, roles: List[str] = ["CEO", "Marketing Manager", "Brand Manager"]) -> List[Dict]:
        """
        Search Google for LinkedIn profiles matching the company and roles.
        """
        contacts = []
        role_query = " OR ".join([f'"{role}"' for role in roles])
        query = f'site:linkedin.com/in "{company_name}" Bali ({role_query})'
        
        logger.info(f"Searching for contacts: {query}")
        
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)
            context = browser.new_context(
                user_agent="Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
                ignore_https_errors=True
            )
            page = context.new_page()
            
            try:
                # Use Bing (DDG is blocked in Indonesia, Google blocks bots)
                search_url = f"https://www.bing.com/search?q={query.replace(' ', '+')}"
                logger.info(f"Navigating to {search_url}")
                page.goto(search_url, timeout=30000)
                
                # Wait for results
                try:
                    page.wait_for_selector('li.b_algo', timeout=10000) 
                except:
                    logger.warning("Bing results not found, checking alternatives...")
                    
                # Extract results
                results = page.locator('li.b_algo').all()
                logger.info(f"Found {len(results)} search results")
                
                if len(results) == 0:
                    logger.warning("No results found. Taking screenshot...")
                    page.screenshot(path=f"bing_debug_{company_name.replace(' ', '_')}.png")
                
                for result in results[:3]: # Limit to top 3 matches per company
                    try:
                        title_el = result.locator('h2 a')
                        
                        if not title_el.count():
                            continue
                            
                        title = title_el.inner_text()
                        link = title_el.get_attribute('href')
                        
                        contacts.append({
                            "name_and_role": title.replace(" - LinkedIn", "").replace(" | LinkedIn", ""),
                            "url": link
                        })
                        
                        # simple parsing of title usually like "Name - Role - Company | LinkedIn"
                        # We just keep the whole title for context
                        
                        contacts.append({
                            "name_and_role": title.replace(" - LinkedIn", ""),
                            "url": link
                        })
                    except Exception:
                        continue
                        
            except Exception as e:
                logger.error(f"Contact search failed for {company_name}: {e}")
            finally:
                browser.close()
                
        return contacts

class GoogleMapsScraper:
    def get_leads(self, query: str = "Hospitality in Bali", limit: int = 20) -> List[Dict]:
        leads = []
        logger.info(f"Starting Google Maps scrape for query: {query}")
        
        with sync_playwright() as p:
            # Add arguments to make it look more like a real browser
            browser = p.chromium.launch(
                headless=True,
                args=["--disable-blink-features=AutomationControlled"]
            )
            context = browser.new_context(
                user_agent="Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
            )
            page = context.new_page()
            
            try:
                # Use direct search URL
                search_url = f"https://www.google.com/maps/search/{query.replace(' ', '+')}"
                logger.info(f"Navigating to {search_url}")
                page.goto(search_url, timeout=60000)
                
                # Handle Consent Dialog (if any)
                try:
                    # Look for "Accept all" or "Accept" button
                    consent_button = page.get_by_role("button", name="Accept all")
                    if consent_button.is_visible(timeout=5000):
                        logger.info("Clicking consent button...")
                        consent_button.click()
                        page.wait_for_load_state("networkidle")
                except Exception:
                    pass

                # Wait for results feed
                try:
                    page.wait_for_selector('div[role="feed"]', timeout=30000)
                except Exception:
                    # Sometimes there is no feed if there is only one result or layout differs
                    logger.warning("Could not find result feed, checking for single result...")
                
                # Scroll loop
                feed = page.locator('div[role="feed"]')
                
                # Retry mechanism for scrolling
                last_count = 0
                retries = 0
                
                while len(leads) < limit:
                    # Check if feed exists
                    if feed.count() > 0:
                        # Scroll down
                        feed.evaluate("element => element.scrollTop = element.scrollHeight")
                        time.sleep(2)
                    else:
                        # If no feed, maybe it's a list on the side or single result
                        break
                        
                    # Extract elements
                    listings = page.locator('div[role="article"]').all()
                    
                    if len(listings) == last_count:
                        retries += 1
                        if retries > 3:
                            break
                    else:
                        retries = 0
                    
                    last_count = len(listings)
                    
                    # Process listings
                    for listing in listings:
                        if len(leads) >= limit:
                            break
                        
                        try:
                            # Extract basic info
                            text_content = listing.inner_text()
                            if not text_content: 
                                continue
                                
                            text = text_content.split('\n')
                            name = text[0] if text else "Unknown"
                            
                            # Skip if name is empty or already in list
                            if not name or any(l['name'] == name for l in leads):
                                continue
                                
                            # Try to get more info if possible, but keeping it simple for stability
                            # Click not implemented to save time/complexity
                            
                            leads.append({
                                "platform": "Google Maps",
                                "name": name,
                                "url": f"https://www.google.com/search?q={name.replace(' ', '+')}+Bali"
                            })
                            logger.info(f"Found G-Maps lead: {name}")
                            
                        except Exception:
                            continue
                            
            except Exception as e:
                logger.error(f"Google Maps scraping failed: {e}")
                # Save screenshot for debugging
                try:
                    page.screenshot(path="gmaps_error.png")
                except:
                    pass
            finally:
                browser.close()
                
        return leads

if __name__ == "__main__":
    # Test run
    ig = InstagramScraper()
    # ig_leads = ig.get_leads(limit=2) # Commented out to save time in dev
    
    gm = GoogleMapsScraper()
    # gm_leads = gm.get_leads(limit=2)
    print("Scraper module loaded.")
