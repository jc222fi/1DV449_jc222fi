## WebSockets
WebSockets är ett API som gör det möjligt att upprätta uppkopplingar mellan en webbläsare och en server som tillåter kommunikation åt båda
hållen. WebSockets är ett bättre och säkrare alternativ till tidigare varianter där man försökte imitera en persistent öppen uppkoppling
med exempelvis timeouts och setInterval. Via WebSockets kan du istället öppna en uppkoppling via en ”socket” och därigenom skicka data när
som helst, samtidigt som servern kan skicka data när som helst, så kallat ”pusha” ut data. På det här viset kringgår du problemet med
HTTP-protokollet där du måste skicka en request för att sedan erhålla ett response. Det blir mer och mer aktuellt att använda WebSockets i
och med det stigande behovet att skapa webbapplikationer som använder realtidsdata, ett exempel kan vara Google Docs där flera personer
kan vara inne och redigera ett och samma dokument samtidigt, och alla medverkande kan se ändringarna i realtid. En av skuggorna som hänger
över tekniken är att man behöver speciella servrar som stödjer den här typen av uppkopplingar, samtidigt som stödet måste finnas hos
klienten. Det är heller inte alla proxyservrar som kommer överens med WebSockets, eftersom de förväntar sig vanlig HTTP-trafik, men de kan
konfigureras specifikt för att kunna hantera WebSockets-trafik.
