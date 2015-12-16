### Vad finns det för krav du måste anpassa dig efter i deolika API:erna?
Leaflet använder sig av i mapbox i sina exempel, och för att inte ta mig vatten över huvudet och lista ut hur jag ersätter mapbox använde
jag också det. För att få använda sig av mapbox måste man registrera sig på deras hemsida för att få tillgång till ders API, så det fick
jag anpassa mig till. Tur för mig att de hade en gratisnivå.

### Hur och hur länge cachar du ditt data för att slippa anropa API:erna i onödan?
Jag använder ett bibliotek som håller ordning på det, och jag cachar i 15 min. Tog en stund att skriva om min kod för att passa biblioteket,
men det kändes som ett bättre alternativ än att skriva ett cache manifest eftersom jag aldrig gjort sånt förr.

### Vad finns det för risker kring säkerhet och stabilitet i din applikation?
Om SR skulle ändra struktur på hur de lagrar datum i sitt API skulle min applikation förmodligen krascha. Kom på i sista stund att jag läste
in värden från SR direkt utan att kolla efter injections, så jag tror att jag har löst den, men i övrigt är det inte mycket validering
implementerad.

### Hur har du tänkt kring säkerheten i din applikation?
Som användare skickar man inte direkt in någonting i nåt formulär, applikationens i stort sett enda funktion är att sammanställa data och
presentera den. Största säkerhetsrisken är om något av API:erna skulle vara komprometterade och någon skulle försöka sig på nånting därifrån.

### Hur har du tänkt kring optimeringen i din applikation?
Jag har lagt alla mina stylesheets i head och alla script längst ner i body. Jag cachar egentligen bara alla trafikmeddelanden, och jag
inser att det finns mer att hämta genom att cacha stylesheets och annat, men jag hann helt enkelt inte läsa på tillräckligt för att fixa det.
