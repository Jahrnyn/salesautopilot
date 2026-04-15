## Hogyan értelmezted a 4. pont szűrés/rendezés követelményt, és miért pont úgy döntöttél?
A szűrés/rendezés követelményt úgy értelmeztem, hogy nem szükséges API oldali megoldást építeni, hanem elegendő egy egyszerű, lokális feldolgozás az első 20 rekordon. Végül egy minimális rendezést valósítottam meg (név/email alapján), mert ez gyorsan implementálható volt, jól demonstrálható, és nem függ az API viselkedésétől. 
A cél itt nem egy komplex kereső volt, hanem az, hogy a követelmény egyszerűen teljesítve legyen.

---

## Melyik AI modell-t használta és miért?
A fejlesztés során Codexet használtam kódgenerálásra és iteratív implementációra, ChatGPT-t pedig tervezésre, validálásra és ellenőrzésre. Egyszerűen azért ezt a kombinációt választottam, mert OpenAI előfizetésem van sajátom, és ez a workflow számomra és kényelmes és nem utolsó sorban pontos.

---

## Hol segített az AI, hol kellett korrigálni vagy más modellt bevonni?
Az AI a teljes folyamatban végig segített, gyakorlatilag végig assisted buildre támaszkodtam. 
Különösen hasznos volt az architektúra megtervezésében, a slice-okra bontásban, valamint abban, hogy konzisztens maradjon a kód és a struktúra. Ugyanakkor több ponton korrigálnom kellett az irányt, például amikor túl általános vagy túl “tökéletesre” tervezett megoldások felé ment volna el, vagy amikor az API dokumentáció bizonytalansága miatt konkrét döntést kellett hozni.

--- 

## Mi az, amit ha újra csinálnád, másképp csinálnál?
Utólag visszanézve valószínűleg túltoltam a strukturáltságot és a nagyon szigorú, kis lépésekre bontott agent workflow-t. 
Maga az integráció viszonylag egyszerű feladat, ehhez képest egy komplexebb, “nagy projektes” megközelítést alkalmaztam. 
Ha újra csinálnám, akkor egy kicsit bátrabban rábíznám az AI-ra az első 80–90%-os implementációt egy részletesebb, egyben kiadott terv alapján, és inkább egy nagyobb review + finomhangolás irányba mennék, sokkal kevesebb iterációval és erősebb automatizációval.