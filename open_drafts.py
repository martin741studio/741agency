import urllib.parse
import subprocess
import time

# Draft Data
pfannes_body = """Hallo Andreas,

mein Name ist Martin Drendel, wir betreuen den digitalen Auftritt eines führenden Unternehmens hier in der Region Würzburg, speziell im Bereich Bau & Industrie.

Wir sind aktuell dabei, die digitale Präsenz unseres Kunden im Landkreis Würzburg weiter zu stärken. Dabei suchen wir gezielt nach starken lokalen Akteuren wie Pfannes Bau, da ihr mit eurer langen Tradition eine feste Größe in der Region seid.

Wir würden gerne eine strategische Kooperation vorschlagen, um für beide Seiten die Domain-Authorität im Landkreis zu erhöhen:

1. **Partner-Indexierung**: Wir nehmen euch offiziell in die Partner-Liste auf unserer Website auf (Citing).
2. **Sinnvoller Content**: Idealerweise schreiben wir einen hochwertigen Blogbeitrag für eure Seite (z.B. über "Logistik & Höhenzugang bei komplexen Bauvorhaben"), der euren Besuchern echten Mehrwert bietet – oder umgekehrt.
3. **Kostenloser Website-Check**: Wir erstellen euch einen kostenlosen Check eurer Seite (Performance) plus eine Task-Liste, die ihr direkt ausführen könnt.

Durch diese Vernetzung signalisieren wir Google unsere lokale Relevanz, was uns beiden hilft, in der Würzburger Region digital besser gefunden zu werden.

Hättet ihr prinzipiell Lust, eure Reichweite im Landkreis gemeinsam mit uns auszubauen? Dann sende ich gerne einen konkreten Vorschlag.

Viele Grüße
Martin Drendel
741.Studio (Executive Digitalteam)"""

template_body_raw = """Hallo [Ansprechpartner],

mein Name ist Martin Drendel, wir betreuen den digitalen Auftritt eines führenden Unternehmens hier in der Region Würzburg, speziell im Bereich Bau & Industrie.

Wir sind aktuell dabei, die digitale Präsenz unseres Kunden im Landkreis weiter zu stärken. Dabei suchen wir gezielt nach starken lokalen Akteuren wie [Company], da ihr als führendes Bauunternehmen eine feste Größe in der Region seid.

Wir würden gerne eine strategische Kooperation vorschlagen, um für beide Seiten die Domain-Authorität im Landkreis zu erhöhen:

1. **Partner-Indexierung**: Wir nehmen euch offiziell in die Partner-Liste auf unserer Website auf (Citing).
2. **Sinnvoller Content**: Idealerweise schreiben wir einen hochwertigen Blogbeitrag für eure Seite (z.B. über "Logistik & Höhenzugang bei komplexen Bauvorhaben"), der euren Besuchern echten Mehrwert bietet – oder umgekehrt.
3. **Kostenloser Website-Check**: Wir erstellen euch einen kostenlosen Check eurer Seite (Performance) plus eine Task-Liste, die ihr direkt ausführen könnt.

Durch diese Vernetzung signalisieren wir Google unsere lokale Relevanz, was uns beiden hilft, in der Region besser gefunden zu werden.

Hättet ihr prinzipiell Lust, eure Reichweite im Landkreis gemeinsam mit uns auszubauen?

Viele Grüße
Martin Drendel
741.Studio (Executive Digitalteam)"""

subject = "Region Würzburg: Digitale Partnerschaft & Netzwerkausbau"

prospects = [
    {"name": "Pfannes Bau", "body": pfannes_body},
    {"name": "Riedel Bau", "template": True},
    {"name": "Glöckle Bau", "template": True},
    {"name": "Beuerlein GmbH", "template": True},
    {"name": "Götz-Bau", "template": True},
    {"name": "Knape Gruppe", "template": True}
]

def open_email(subject, body):
    # Encode
    subject_enc = urllib.parse.quote(subject)
    body_enc = urllib.parse.quote(body)
    
    # Construct URL
    url = f"mailto:?subject={subject_enc}&body={body_enc}"
    
    # Open
    # Using 'open' triggers default mail client. 
    # Attempting to target Airmail if possible, but 'open url' usually respects default.
    cmd = ["open", url]
    print(f"Opening draft...")
    subprocess.call(cmd)
    time.sleep(1) # Wait a bit between opens to avoid overwhelming the app

for p in prospects:
    print(f"Processing {p['name']}...")
    if p.get("template"):
        final_body = template_body_raw.replace("[Company]", p['name'])
    else:
        final_body = p['body']
        
    open_email(subject, final_body)

print("Done.")
