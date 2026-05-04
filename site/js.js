const SUPPORTED_LOCALES = ["de-DE", "en-GB", "it-IT", "es-ES", "fr-FR", "en-US"];
const LANGUAGE_PRIMARY = {
de: "de-DE",
en: "en-US",
it: "it-IT",
es: "es-ES",
fr: "fr-FR"
};
const LOCALES = {
"en-US": {
meta: {
title: "WP BrowserUpdate for WordPress",
description: "WP BrowserUpdate shows controlled browser update notices in WordPress and keeps visitor-facing runtime assets on your site.",
ogDescription: "Controlled browser update notices for WordPress with local runtime assets and clear settings."
},
skip: "Skip to content",
language: {
selectAria: "Choose language"
},
actions: {
download: "Installation",
release: "Latest Release"
},
hero: {
note: "WordPress Plugin",
title: "Tell visitors when their browser is too old.",
lead: "WP BrowserUpdate adds a clear browser update notice to WordPress. You set the browser limits, the text and the links; the plugin keeps the visitor-facing files on your own site."
},
visitors: {
title: "What visitors see",
lead: "A short, direct notice appears only when a visitor uses a browser below your configured threshold.",
notice: {
title: "A clear update prompt",
body: "The notice explains that the browser is outdated and points people to a current browser download."
},
timing: {
title: "Only when needed",
body: "Modern browsers stay out of the flow. Older browsers get the nudge you chose."
},
page: {
title: "No third-party runtime request",
body: "The public page loads the packaged runtime from your WordPress installation."
}
},
local: {
title: "Built for stricter sites",
lead: "Many sites use CSPs, privacy tools or tracker blocking. WP BrowserUpdate avoids a common failure point by serving the notification assets from the same site as the page.",
sameOrigin: "Same-origin scripts and styles for the browser notice.",
control: "Configuration stays in the WordPress admin area.",
upstream: "browser-update.org remains credited as the upstream notification project."
},
configure: {
title: "Control the notice",
lead: "Keep the default setup simple, or tune the details when your audience needs stricter browser guidance.",
versions: {
title: "Browser thresholds",
body: "Set defaults, major versions, negative offsets or exact dotted versions such as 137.0.3912.63."
},
edge: {
title: "Edge and Internet Explorer",
body: "Treat modern Edge separately from legacy Internet Explorer."
},
test: {
title: "Test mode",
body: "Force the notice while setting up, then turn test mode off for real traffic."
},
style: {
title: "Styling",
body: "Add site-specific CSS without printing raw style blocks into the page."
},
text: {
title: "Text and links",
body: "Adjust the wording and browser download targets for your visitors."
},
admin: {
title: "Native admin screen",
body: "Use a focused WordPress settings page, not a separate dashboard."
},
reminder: {
title: "Reminder rhythm",
body: "Choose how soon the notice can reappear after a visitor has seen it."
},
coverage: {
title: "Audience coverage",
body: "Decide how the notice handles mobile, insecure or unsupported browsers."
}
},
links: {
title: "Install the plugin. Keep the source visible.",
lead: "Use WordPress.org for normal installation and updates. Use GitHub for release notes, source review and issue tracking.",
wordpress: "Open WordPress.org",
repo: "Open GitHub",
note: "Requires WordPress 6.0 or newer and PHP 7.4 or newer. The browser notice runtime is packaged with the plugin and documented in the assets."
},
footer: {
copy: "WP BrowserUpdate by MacSteini."
}
},
"en-GB": {
meta: {
title: "WP BrowserUpdate for WordPress",
description: "WP BrowserUpdate shows controlled browser update notices in WordPress and keeps visitor-facing runtime assets on your site.",
ogDescription: "Controlled browser update notices for WordPress with local runtime assets and clear settings."
},
skip: "Skip to content",
language: {
selectAria: "Choose language"
},
actions: {
download: "Installation",
release: "Latest Release"
},
hero: {
note: "WordPress Plugin",
title: "Tell visitors when their browser is too old.",
lead: "WP BrowserUpdate adds a clear browser update notice to WordPress. You set the browser limits, the text and the links; the plugin keeps the visitor-facing files on your own site."
},
visitors: {
title: "What visitors see",
lead: "A short, direct notice appears only when a visitor uses a browser below your configured threshold.",
notice: {
title: "A clear update prompt",
body: "The notice explains that the browser is outdated and points people to a current browser download."
},
timing: {
title: "Only when needed",
body: "Modern browsers stay out of the flow. Older browsers get the nudge you chose."
},
page: {
title: "No third-party runtime request",
body: "The public page loads the packaged runtime from your WordPress installation."
}
},
local: {
title: "Built for stricter sites",
lead: "Many sites use CSPs, privacy tools or tracker blocking. WP BrowserUpdate avoids a common failure point by serving the notification assets from the same site as the page.",
sameOrigin: "Same-origin scripts and styles for the browser notice.",
control: "Configuration stays in the WordPress admin area.",
upstream: "browser-update.org remains credited as the upstream notification project."
},
configure: {
title: "Control the notice",
lead: "Keep the default setup simple, or tune the details when your audience needs stricter browser guidance.",
versions: {
title: "Browser thresholds",
body: "Set defaults, major versions, negative offsets or exact dotted versions such as 137.0.3912.63."
},
edge: {
title: "Edge and Internet Explorer",
body: "Treat modern Edge separately from legacy Internet Explorer."
},
test: {
title: "Test mode",
body: "Force the notice while setting up, then turn test mode off for real traffic."
},
style: {
title: "Styling",
body: "Add site-specific CSS without printing raw style blocks into the page."
},
text: {
title: "Text and links",
body: "Adjust the wording and browser download targets for your visitors."
},
admin: {
title: "Native admin screen",
body: "Use a focused WordPress settings page, not a separate dashboard."
},
reminder: {
title: "Reminder rhythm",
body: "Choose how soon the notice can reappear after a visitor has seen it."
},
coverage: {
title: "Audience coverage",
body: "Decide how the notice handles mobile, insecure or unsupported browsers."
}
},
links: {
title: "Install the plugin. Keep the source visible.",
lead: "Use WordPress.org for normal installation and updates. Use GitHub for release notes, source review and issue tracking.",
wordpress: "Open WordPress.org",
repo: "Open GitHub",
note: "Requires WordPress 6.0 or newer and PHP 7.4 or newer. The browser notice runtime is packaged with the plugin and documented in the assets."
},
footer: {
copy: "WP BrowserUpdate by MacSteini."
}
},
"de-DE": {
meta: {
title: "WP BrowserUpdate für WordPress",
description: "WP BrowserUpdate zeigt kontrollierte Browser-Update-Hinweise in WordPress und lädt die sichtbaren Runtime-Dateien von der eigenen Website.",
ogDescription: "Kontrollierte Browser-Update-Hinweise für WordPress mit lokalen Runtime-Dateien und klaren Einstellungen."
},
skip: "Zum Inhalt springen",
language: {
selectAria: "Sprache auswählen"
},
actions: {
download: "Installation",
release: "Neuestes Release"
},
hero: {
note: "WordPress Plugin",
title: "Sag Besuchern, wenn ihr Browser zu alt ist.",
lead: "WP BrowserUpdate ergänzt WordPress um einen klaren Browser-Update-Hinweis. Du bestimmst Browsergrenzen, Text und Links; die sichtbaren Dateien lädt das Plugin von deiner eigenen Website."
},
visitors: {
title: "Was Besucher sehen",
lead: "Ein kurzer, direkter Hinweis erscheint nur dann, wenn ein Browser unter deiner eingestellten Grenze liegt.",
notice: {
title: "Ein klarer Update-Hinweis",
body: "Der Hinweis erklärt, dass der Browser veraltet ist, und führt zu einem aktuellen Browser-Download."
},
timing: {
title: "Nur wenn es nötig ist",
body: "Moderne Browser bleiben unbehelligt. Alte Browser bekommen den Hinweis, den du festlegst."
},
page: {
title: "Keine externe Runtime-Anfrage",
body: "Die öffentliche Seite lädt die mitgelieferte Runtime aus deiner WordPress-Installation."
}
},
local: {
title: "Für strengere Websites gebaut",
lead: "Viele Websites nutzen CSPs, Privacy-Tools oder Tracker-Blocker. WP BrowserUpdate vermeidet eine typische Fehlerquelle, indem die Hinweisdateien vom selben Ursprung wie die Seite geladen werden.",
sameOrigin: "Scripts und Styles für den Browser-Hinweis vom selben Ursprung.",
control: "Die Konfiguration bleibt im WordPress-Adminbereich.",
upstream: "browser-update.org bleibt als Upstream-Projekt der Hinweislogik genannt."
},
configure: {
title: "Den Hinweis steuern",
lead: "Starte mit einfachen Standardwerten oder stelle Details ein, wenn deine Zielgruppe strengere Browsergrenzen braucht.",
versions: {
title: "Browsergrenzen",
body: "Nutze Standardwerte, Hauptversionen, negative Abstände oder exakte Versionen wie 137.0.3912.63."
},
edge: {
title: "Edge und Internet Explorer",
body: "Behandle modernes Edge getrennt vom alten Internet Explorer."
},
test: {
title: "Testmodus",
body: "Erzwinge den Hinweis beim Einrichten und schalte den Testmodus danach für echte Besucher aus."
},
style: {
title: "Styling",
body: "Ergänze eigenes CSS, ohne rohe Style-Blöcke in die Seite zu drucken."
},
text: {
title: "Text und Links",
body: "Passe Wortlaut und Browser-Downloadziele an deine Besucher an."
},
admin: {
title: "Native Adminseite",
body: "Nutze eine fokussierte WordPress-Einstellungsseite statt eines eigenen Dashboards."
},
reminder: {
title: "Erinnerungsrhythmus",
body: "Lege fest, wann der Hinweis nach dem ersten Anzeigen wieder erscheinen darf."
},
coverage: {
title: "Zielgruppenabdeckung",
body: "Bestimme, wie der Hinweis mit mobilen, unsicheren oder nicht unterstützten Browsern umgeht."
}
},
links: {
title: "Plugin installieren. Quelle prüfen.",
lead: "Für normale Installation und Updates ist WordPress.org der richtige Weg. GitHub dient für Release Notes, Quellcodeprüfung und Issues.",
wordpress: "WordPress.org öffnen",
repo: "GitHub öffnen",
note: "Erfordert WordPress 6.0 oder neuer und PHP 7.4 oder neuer. Die Browser-Hinweis-Runtime ist im Plugin enthalten und in den Assets dokumentiert."
},
footer: {
copy: "WP BrowserUpdate von MacSteini."
}
},
"it-IT": {
meta: {
title: "WP BrowserUpdate per WordPress",
description: "WP BrowserUpdate mostra avvisi controllati di aggiornamento del browser in WordPress e carica gli asset runtime dal tuo sito.",
ogDescription: "Avvisi controllati di aggiornamento del browser per WordPress con asset runtime locali e impostazioni chiare."
},
skip: "Vai al contenuto",
language: {
selectAria: "Scegli lingua"
},
actions: {
download: "Installazione",
release: "Ultima release"
},
hero: {
note: "WordPress Plugin",
title: "Avvisa i visitatori quando il browser è troppo vecchio.",
lead: "WP BrowserUpdate aggiunge a WordPress un avviso chiaro per aggiornare il browser. Imposti limiti, testo e link; il plugin carica i file visibili dal tuo sito."
},
visitors: {
title: "Cosa vedono i visitatori",
lead: "Un avviso breve e diretto appare solo quando un browser è sotto la soglia configurata.",
notice: {
title: "Un avviso chiaro",
body: "L’avviso spiega che il browser è obsoleto e porta a un download aggiornato."
},
timing: {
title: "Solo quando serve",
body: "I browser moderni non vengono disturbati. Quelli vecchi ricevono il messaggio che hai scelto."
},
page: {
title: "Nessuna richiesta runtime esterna",
body: "La pagina pubblica carica il runtime incluso dalla tua installazione WordPress."
}
},
local: {
title: "Pensato per siti più rigorosi",
lead: "Molti siti usano CSP, strumenti privacy o blocchi anti-tracker. WP BrowserUpdate evita un punto di rottura comune servendo gli asset di avviso dallo stesso sito della pagina.",
sameOrigin: "Script e stili same-origin per l’avviso del browser.",
control: "La configurazione resta nell’area admin di WordPress.",
upstream: "browser-update.org resta citato come progetto upstream della logica di avviso."
},
configure: {
title: "Controlla l’avviso",
lead: "Mantieni la configurazione semplice oppure regola i dettagli quando il tuo pubblico richiede limiti browser più severi.",
versions: {
title: "Soglie browser",
body: "Usa valori predefiniti, versioni principali, offset negativi o versioni esatte come 137.0.3912.63."
},
edge: {
title: "Edge e Internet Explorer",
body: "Gestisci Edge moderno separatamente dal vecchio Internet Explorer."
},
test: {
title: "Modalità test",
body: "Forza l’avviso durante la configurazione, poi disattiva il test per il traffico reale."
},
style: {
title: "Stile",
body: "Aggiungi CSS specifico del sito senza stampare blocchi style grezzi nella pagina."
},
text: {
title: "Testo e link",
body: "Adatta il testo e le destinazioni di download dei browser ai tuoi visitatori."
},
admin: {
title: "Schermata admin nativa",
body: "Usa una pagina impostazioni WordPress mirata, non una dashboard separata."
},
reminder: {
title: "Ritmo del promemoria",
body: "Scegli dopo quanto tempo l’avviso può riapparire dopo essere stato visto."
},
coverage: {
title: "Copertura del pubblico",
body: "Decidi come l’avviso gestisce browser mobili, non sicuri o non supportati."
}
},
links: {
title: "Installa il plugin. Controlla il codice.",
lead: "Usa WordPress.org per installazione e aggiornamenti normali. GitHub serve per note di rilascio, revisione del codice e issue.",
wordpress: "Apri WordPress.org",
repo: "Apri GitHub",
note: "Richiede WordPress 6.0 o superiore e PHP 7.4 o superiore. Il runtime dell’avviso browser è incluso nel plugin e documentato negli asset."
},
footer: {
copy: "WP BrowserUpdate di MacSteini."
}
},
"es-ES": {
meta: {
title: "WP BrowserUpdate para WordPress",
description: "WP BrowserUpdate muestra avisos controlados de actualización del navegador en WordPress y carga los archivos runtime desde tu sitio.",
ogDescription: "Avisos controlados de actualización del navegador para WordPress con runtime local y ajustes claros."
},
skip: "Saltar al contenido",
language: {
selectAria: "Elegir idioma"
},
actions: {
download: "Instalación",
release: "Última release"
},
hero: {
note: "WordPress Plugin",
title: "Avisa cuando el navegador del visitante es demasiado antiguo.",
lead: "WP BrowserUpdate añade a WordPress un aviso claro para actualizar el navegador. Tú defines límites, texto y enlaces; el plugin carga los archivos visibles desde tu propio sitio."
},
visitors: {
title: "Qué ven los visitantes",
lead: "Aparece un aviso breve y directo solo cuando un navegador está por debajo del umbral configurado.",
notice: {
title: "Un aviso claro",
body: "El aviso explica que el navegador está obsoleto y lleva a una descarga actual."
},
timing: {
title: "Solo cuando hace falta",
body: "Los navegadores modernos no se interrumpen. Los antiguos reciben el aviso que elegiste."
},
page: {
title: "Sin petición runtime externa",
body: "La página pública carga el runtime incluido desde tu instalación de WordPress."
}
},
local: {
title: "Preparado para sitios más estrictos",
lead: "Muchos sitios usan CSP, herramientas de privacidad o bloqueadores de rastreo. WP BrowserUpdate evita un fallo habitual al servir los archivos del aviso desde el mismo sitio que la página.",
sameOrigin: "Scripts y estilos same-origin para el aviso del navegador.",
control: "La configuración permanece en el área de administración de WordPress.",
upstream: "browser-update.org sigue acreditado como proyecto upstream de la lógica de aviso."
},
configure: {
title: "Controla el aviso",
lead: "Mantén la configuración sencilla o ajusta los detalles cuando tu audiencia necesite límites de navegador más estrictos.",
versions: {
title: "Umbrales de navegador",
body: "Usa valores predeterminados, versiones mayores, desplazamientos negativos o versiones exactas como 137.0.3912.63."
},
edge: {
title: "Edge e Internet Explorer",
body: "Trata Edge moderno por separado del Internet Explorer heredado."
},
test: {
title: "Modo de prueba",
body: "Fuerza el aviso durante la configuración y desactívalo después para el tráfico real."
},
style: {
title: "Estilo",
body: "Añade CSS específico del sitio sin imprimir bloques style sin procesar en la página."
},
text: {
title: "Texto y enlaces",
body: "Ajusta el texto y los destinos de descarga del navegador para tus visitantes."
},
admin: {
title: "Pantalla admin nativa",
body: "Usa una página de ajustes de WordPress enfocada, no un panel separado."
},
reminder: {
title: "Ritmo del recordatorio",
body: "Elige cuándo puede volver a aparecer el aviso después de que un visitante lo haya visto."
},
coverage: {
title: "Cobertura del público",
body: "Decide cómo trata el aviso navegadores móviles, inseguros o no compatibles."
}
},
links: {
title: "Instala el plugin. Revisa la fuente.",
lead: "Usa WordPress.org para instalación y actualizaciones normales. GitHub sirve para notas de versión, revisión de código e incidencias.",
wordpress: "Abrir WordPress.org",
repo: "Abrir GitHub",
note: "Requiere WordPress 6.0 o superior y PHP 7.4 o superior. El runtime del aviso del navegador está incluido en el plugin y documentado en los assets."
},
footer: {
copy: "WP BrowserUpdate de MacSteini."
}
},
"fr-FR": {
meta: {
title: "WP BrowserUpdate pour WordPress",
description: "WP BrowserUpdate affiche des avis contrôlés de mise à jour du navigateur dans WordPress et charge les fichiers runtime depuis votre site.",
ogDescription: "Avis contrôlés de mise à jour du navigateur pour WordPress avec runtime local et réglages clairs."
},
skip: "Aller au contenu",
language: {
selectAria: "Choisir la langue"
},
actions: {
download: "Installation",
release: "Dernière release"
},
hero: {
note: "WordPress Plugin",
title: "Prévenez les visiteurs quand leur navigateur est trop ancien.",
lead: "WP BrowserUpdate ajoute à WordPress un avis clair de mise à jour du navigateur. Vous définissez les limites, le texte et les liens; l’extension charge les fichiers visibles depuis votre propre site."
},
visitors: {
title: "Ce que voient les visiteurs",
lead: "Un avis court et direct apparaît uniquement lorsqu’un navigateur passe sous le seuil configuré.",
notice: {
title: "Un avis clair",
body: "L’avis explique que le navigateur est obsolète et renvoie vers un téléchargement actuel."
},
timing: {
title: "Seulement si nécessaire",
body: "Les navigateurs modernes ne sont pas interrompus. Les anciens reçoivent l’avis que vous avez choisi."
},
page: {
title: "Aucune requête runtime externe",
body: "La page publique charge le runtime inclus depuis votre installation WordPress."
}
},
local: {
title: "Pensé pour les sites plus stricts",
lead: "De nombreux sites utilisent des CSP, des outils de confidentialité ou des bloqueurs de traqueurs. WP BrowserUpdate évite un point de panne fréquent en servant les fichiers d’avis depuis le même site que la page.",
sameOrigin: "Scripts et styles same-origin pour l’avis navigateur.",
control: "La configuration reste dans l’administration WordPress.",
upstream: "browser-update.org reste crédité comme projet upstream de la logique d’avis."
},
configure: {
title: "Contrôlez l’avis",
lead: "Gardez une configuration simple ou ajustez les détails lorsque votre public nécessite des limites navigateur plus strictes.",
versions: {
title: "Seuils navigateur",
body: "Utilisez les valeurs par défaut, les versions majeures, les décalages négatifs ou des versions exactes comme 137.0.3912.63."
},
edge: {
title: "Edge et Internet Explorer",
body: "Traitez Edge moderne séparément de l’ancien Internet Explorer."
},
test: {
title: "Mode test",
body: "Forcez l’avis pendant la configuration, puis désactivez le test pour le trafic réel."
},
style: {
title: "Style",
body: "Ajoutez du CSS propre au site sans imprimer de blocs style bruts dans la page."
},
text: {
title: "Texte et liens",
body: "Adaptez le texte et les destinations de téléchargement du navigateur à vos visiteurs."
},
admin: {
title: "Écran admin natif",
body: "Utilisez une page de réglages WordPress ciblée, pas un tableau de bord séparé."
},
reminder: {
title: "Rythme de rappel",
body: "Choisissez quand l’avis peut réapparaître après avoir été vu par un visiteur."
},
coverage: {
title: "Couverture du public",
body: "Décidez comment l’avis gère les navigateurs mobiles, non sécurisés ou non pris en charge."
}
},
links: {
title: "Installez l’extension. Vérifiez la source.",
lead: "Utilisez WordPress.org pour l’installation et les mises à jour normales. GitHub sert aux notes de version, à la revue du code et aux tickets.",
wordpress: "Ouvrir WordPress.org",
repo: "Ouvrir GitHub",
note: "Nécessite WordPress 6.0 ou plus récent et PHP 7.4 ou plus récent. Le runtime de l’avis navigateur est inclus dans l’extension et documenté dans les assets."
},
footer: {
copy: "WP BrowserUpdate par MacSteini."
}
}
};
function readPath(source, path) {
return path.split(".").reduce(function(value, key) {
return value && Object.prototype.hasOwnProperty.call(value, key) ? value[key] : undefined;
}, source);
}
function normaliseLocale(value) {
if (!value) {
return "";
}
return String(value).replace("_", "-");
}
function chooseLocale(candidates) {
for (const candidate of candidates) {
const locale = normaliseLocale(candidate);
if (SUPPORTED_LOCALES.includes(locale)) {
return locale;
}
}
for (const candidate of candidates) {
const language = normaliseLocale(candidate).slice(0, 2).toLowerCase();
if (Object.prototype.hasOwnProperty.call(LANGUAGE_PRIMARY, language)) {
return LANGUAGE_PRIMARY[language];
}
}
return "en-US";
}
function updateMeta(locale, strings) {
document.documentElement.lang = locale;
document.body.dataset.locale = locale;
document.title = strings.meta.title;
document.querySelector('meta[name="description"]').setAttribute("content", strings.meta.description);
document.querySelector('meta[property="og:title"]').setAttribute("content", strings.meta.title);
document.querySelector('meta[property="og:description"]').setAttribute("content", strings.meta.ogDescription);
document.querySelector('meta[name="twitter:title"]').setAttribute("content", strings.meta.title);
document.querySelector('meta[name="twitter:description"]').setAttribute("content", strings.meta.ogDescription);
}
function applyLocale(locale) {
const strings = LOCALES[locale] || LOCALES["en-US"];
updateMeta(locale, strings);
document.querySelectorAll("[data-i18n]").forEach(function(element) {
const value = readPath(strings, element.dataset.i18n);
if (typeof value === "string") {
element.textContent = value;
}
});
document.querySelectorAll("[data-i18n-attr]").forEach(function(element) {
element.dataset.i18nAttr.split(";").forEach(function(entry) {
const parts = entry.split(":");
const attr = parts[0];
const key = parts[1];
const value = readPath(strings, key);
if (attr && typeof value === "string") {
element.setAttribute(attr, value);
}
});
});
const selector = document.getElementById("language-select");
if (selector) {
selector.value = locale;
}
}
function initialLocale() {
const stored = localStorage.getItem("wpbu-locale");
const browserLocales = navigator.languages && navigator.languages.length ? navigator.languages : [navigator.language];
return chooseLocale(stored ? [stored].concat(browserLocales) : browserLocales);
}
document.addEventListener("DOMContentLoaded", function() {
const selector = document.getElementById("language-select");
applyLocale(initialLocale());
if (selector) {
selector.addEventListener("change", function(event) {
const locale = chooseLocale([event.target.value]);
localStorage.setItem("wpbu-locale", locale);
applyLocale(locale);
});
}
});
