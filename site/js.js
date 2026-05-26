const SUPPORTED_LOCALES = ["de-DE", "en-GB", "it-IT", "es-ES", "fr-FR", "en-US"];
const LANGUAGE_PRIMARY = {
de: "de-DE",
en: "en-US",
it: "it-IT",
es: "es-ES",
fr: "fr-FR"
};
const THEME_MODES = ["auto", "light", "dark"];
const THEME_STORAGE_KEY = "wpbu-theme";
const LOCALE_STORAGE_KEY = "wpbu-locale";
const systemTheme = window.matchMedia("(prefers-color-scheme: dark)");
const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

const LOCALES = {
"en-US": {
meta: {
title: "WP BrowserUpdate for WordPress",
description: "WP BrowserUpdate helps WordPress sites show clear update notices to visitors using outdated browsers.",
ogDescription: "A focused WordPress plugin for browser update notices, local runtime files and practical settings."
},
skip: "Skip to content",
language: {
navAria: "Choose language"
},
theme: {
labelAuto: "Auto",
labelLight: "Light",
labelDark: "Dark",
ariaAuto: "Theme: automatic",
ariaLight: "Theme: light",
ariaDark: "Theme: dark"
},
hero: {
title: "A clear notice when a browser is out of date.",
lead: "WP BrowserUpdate helps WordPress sites warn people when a browser is too old for the intended site experience.",
support: "Site owners define the support threshold. The plugin handles the notice, the wording and the visitor-facing files from the WordPress site."
},
preview: {
title: "Your browser is out of date.",
body: "Please update your browser to use this website safely and comfortably.",
action: "Update browser"
},
story: {
title: "Start with the visitor, not the browser list.",
lead: "The plugin covers a simple situation: someone opens a site with an outdated browser, and the site can offer a useful next step without disturbing everyone else.",
detect: {
title: "Check browser support",
body: "Supported browser versions can be defined with defaults, major versions or exact dotted versions when that level of control is needed."
},
explain: {
title: "Explain the issue",
body: "A short notice can use site-specific text and browser download targets, so visitors are not left to guess."
},
stay: {
title: "Stay in WordPress",
body: "The notice is configured from a normal settings screen and can be tested before it appears for real traffic."
}
},
runtime: {
title: "Built to avoid avoidable frontend surprises.",
lead: "The browser notice runtime is bundled with the plugin, so the public page does not need to fetch the notice scripts or styles from browser-update.org.",
sameOrigin: "Visitor-facing runtime files load from the WordPress site.",
csp: "This is friendlier to strict Content Security Policies and tracker blockers.",
credit: "browser-update.org remains the credited upstream project for the notification runtime."
},
configure: {
title: "Control the notice without turning it into a project.",
lead: "Most sites can keep the defaults. When more precision is needed, the settings stay focused on decisions a site owner can understand.",
thresholds: {
title: "Browser thresholds",
body: "Defaults, major versions, negative offsets and exact dotted versions such as 137.0.3912.63 are supported."
},
targets: {
title: "Separate targets",
body: "Modern Edge, legacy Internet Explorer and additional browser targets can be handled separately."
},
reminders: {
title: "Reminder rhythm",
body: "The reappearance delay controls when a dismissed notice may return for the same visitor."
},
appearance: {
title: "Text and appearance",
body: "Wording, links and site-specific CSS can be adjusted without printing raw style blocks into the page."
},
admin: {
title: "Native admin screen",
body: "Configuration stays in one focused WordPress settings page instead of a separate dashboard."
},
testing: {
title: "Safe testing",
body: "Test mode can force the notice during setup and is disabled again before real visitors see the site."
}
},
links: {
title: "Install from WordPress.org. Review the source on GitHub.",
lead: "For normal WordPress installation and updates, use the plugin directory. For source review and issue tracking, use the GitHub repository.",
wordpress: "Open WordPress.org",
github: "Open GitHub",
note: "Requires WordPress 6.0 or newer and PHP 7.4 or newer."
},
footer: {
navAria: "Footer",
top: "Back to top",
wordpress: "WordPress.org",
github: "GitHub"
}
},
"en-GB": {
meta: {
title: "WP BrowserUpdate for WordPress",
description: "WP BrowserUpdate helps WordPress sites show clear update notices to visitors using outdated browsers.",
ogDescription: "A focused WordPress plugin for browser update notices, local runtime files and practical settings."
},
skip: "Skip to content",
language: {
navAria: "Choose language"
},
theme: {
labelAuto: "Auto",
labelLight: "Light",
labelDark: "Dark",
ariaAuto: "Theme: automatic",
ariaLight: "Theme: light",
ariaDark: "Theme: dark"
},
hero: {
title: "A clear notice when a browser is out of date.",
lead: "WP BrowserUpdate helps WordPress sites warn people when a browser is too old for the intended site experience.",
support: "Site owners define the support threshold. The plugin handles the notice, the wording and the visitor-facing files from the WordPress site."
},
preview: {
title: "Your browser is out of date.",
body: "Please update your browser to use this website safely and comfortably.",
action: "Update browser"
},
story: {
title: "Start with the visitor, not the browser list.",
lead: "The plugin covers a simple situation: someone opens a site with an outdated browser, and the site can offer a useful next step without disturbing everyone else.",
detect: {
title: "Check browser support",
body: "Supported browser versions can be defined with defaults, major versions or exact dotted versions when that level of control is needed."
},
explain: {
title: "Explain the issue",
body: "A short notice can use site-specific text and browser download targets, so visitors are not left to guess."
},
stay: {
title: "Stay in WordPress",
body: "The notice is configured from a normal settings screen and can be tested before it appears for real traffic."
}
},
runtime: {
title: "Built to avoid avoidable frontend surprises.",
lead: "The browser notice runtime is bundled with the plugin, so the public page does not need to fetch the notice scripts or styles from browser-update.org.",
sameOrigin: "Visitor-facing runtime files load from the WordPress site.",
csp: "This is friendlier to strict Content Security Policies and tracker blockers.",
credit: "browser-update.org remains the credited upstream project for the notification runtime."
},
configure: {
title: "Control the notice without turning it into a project.",
lead: "Most sites can keep the defaults. When more precision is needed, the settings stay focused on decisions a site owner can understand.",
thresholds: {
title: "Browser thresholds",
body: "Defaults, major versions, negative offsets and exact dotted versions such as 137.0.3912.63 are supported."
},
targets: {
title: "Separate targets",
body: "Modern Edge, legacy Internet Explorer and additional browser targets can be handled separately."
},
reminders: {
title: "Reminder rhythm",
body: "The reappearance delay controls when a dismissed notice may return for the same visitor."
},
appearance: {
title: "Text and appearance",
body: "Wording, links and site-specific CSS can be adjusted without printing raw style blocks into the page."
},
admin: {
title: "Native admin screen",
body: "Configuration stays in one focused WordPress settings page instead of a separate dashboard."
},
testing: {
title: "Safe testing",
body: "Test mode can force the notice during setup and is disabled again before real visitors see the site."
}
},
links: {
title: "Install from WordPress.org. Review the source on GitHub.",
lead: "For normal WordPress installation and updates, use the plugin directory. For source review and issue tracking, use the GitHub repository.",
wordpress: "Open WordPress.org",
github: "Open GitHub",
note: "Requires WordPress 6.0 or newer and PHP 7.4 or newer."
},
footer: {
navAria: "Footer",
top: "Back to top",
wordpress: "WordPress.org",
github: "GitHub"
}
},
"de-DE": {
meta: {
title: "WP BrowserUpdate für WordPress",
description: "WP BrowserUpdate hilft WordPress-Websites, klare Update-Hinweise für Besucher mit veralteten Browsern zu zeigen.",
ogDescription: "Ein fokussiertes WordPress-Plugin für Browser-Update-Hinweise, lokale Runtime-Dateien und verständliche Einstellungen."
},
skip: "Zum Inhalt springen",
language: {
navAria: "Sprache auswählen"
},
theme: {
labelAuto: "Auto",
labelLight: "Hell",
labelDark: "Dunkel",
ariaAuto: "Darstellung: automatisch",
ariaLight: "Darstellung: hell",
ariaDark: "Darstellung: dunkel"
},
hero: {
title: "Ein klarer Hinweis, wenn ein Browser zu alt ist.",
lead: "WP BrowserUpdate hilft WordPress-Websites, Besucher zu informieren, wenn ein Browser zu alt für die gewünschte Nutzung ist.",
support: "Website-Betreiber legen die Support-Grenze fest. Das Plugin kümmert sich um Hinweis, Texte und sichtbare Dateien von der WordPress-Website."
},
preview: {
title: "Dein Browser ist veraltet.",
body: "Bitte aktualisiere deinen Browser, um diese Website sicher und komfortabel zu nutzen.",
action: "Browser aktualisieren"
},
story: {
title: "Erst der Besucher, dann die Browserliste.",
lead: "Die Situation ist einfach: Jemand öffnet eine Website mit einem veralteten Browser, und die Seite bietet einen sinnvollen nächsten Schritt an, ohne alle anderen zu stören.",
detect: {
title: "Browser-Support prüfen",
body: "Unterstützte Browser-Versionen lassen sich über Defaults, Major-Versionen oder exakte dotted Versionsnummern festlegen."
},
explain: {
title: "Problem erklären",
body: "Ein kurzer Hinweis kann Website-spezifische Texte und Browser-Download-Ziele verwenden, damit Besucher nicht raten müssen."
},
stay: {
title: "In WordPress bleiben",
body: "Der Hinweis wird über eine normale Einstellungsseite konfiguriert und kann vor echtem Traffic getestet werden."
}
},
runtime: {
title: "Gebaut, um vermeidbare Frontend-Probleme zu vermeiden.",
lead: "Die Runtime für den Browser-Hinweis ist im Plugin enthalten. Die öffentliche Seite muss die Hinweis-Skripte und Styles daher nicht von browser-update.org laden.",
sameOrigin: "Die sichtbaren Runtime-Dateien laden von der WordPress-Website.",
csp: "Das ist freundlicher für strikte Content Security Policies und Tracker-Blocker.",
credit: "browser-update.org bleibt als Upstream-Projekt der Hinweis-Runtime genannt."
},
configure: {
title: "Hinweis steuern, ohne ein Projekt daraus zu machen.",
lead: "Für viele Websites reichen die Defaults. Wenn mehr Präzision nötig ist, bleiben die Einstellungen auf verständliche Entscheidungen fokussiert.",
thresholds: {
title: "Browsergrenzen",
body: "Defaults, Major-Versionen, negative Offsets und exakte dotted Versionen wie 137.0.3912.63 werden unterstützt."
},
targets: {
title: "Getrennte Ziele",
body: "Moderner Edge, alter Internet Explorer und zusätzliche Browserziele können separat behandelt werden."
},
reminders: {
title: "Erinnerungsrhythmus",
body: "Der Erinnerungsabstand steuert, wann ein geschlossener Hinweis für denselben Besucher wieder erscheinen darf."
},
appearance: {
title: "Text und Darstellung",
body: "Wortlaut, Links und Website-CSS lassen sich anpassen, ohne rohe Style-Blöcke in die Seite zu schreiben."
},
admin: {
title: "Native Admin-Seite",
body: "Die Konfiguration bleibt in einer fokussierten WordPress-Einstellungsseite statt in einem separaten Dashboard."
},
testing: {
title: "Sicher testen",
body: "Der Testmodus kann den Hinweis beim Einrichten erzwingen und wird vor echtem Traffic wieder deaktiviert."
}
},
links: {
title: "Über WordPress.org installieren. Quellcode auf GitHub prüfen.",
lead: "Für normale Installation und Updates ist das Plugin-Verzeichnis der richtige Weg. Für Quellcodeprüfung und Issues dient das GitHub-Repository.",
wordpress: "WordPress.org öffnen",
github: "GitHub öffnen",
note: "Erfordert WordPress 6.0 oder neuer und PHP 7.4 oder neuer."
},
footer: {
navAria: "Footer",
top: "Nach oben",
wordpress: "WordPress.org",
github: "GitHub"
}
},
"it-IT": {
meta: {
title: "WP BrowserUpdate per WordPress",
description: "WP BrowserUpdate aiuta i siti WordPress a mostrare avvisi chiari ai visitatori che usano browser obsoleti.",
ogDescription: "Un plugin WordPress mirato per avvisi di aggiornamento del browser, file runtime locali e impostazioni pratiche."
},
skip: "Vai al contenuto",
language: {
navAria: "Scegli lingua"
},
theme: {
labelAuto: "Auto",
labelLight: "Chiaro",
labelDark: "Scuro",
ariaAuto: "Tema: automatico",
ariaLight: "Tema: chiaro",
ariaDark: "Tema: scuro"
},
hero: {
title: "Un avviso chiaro quando un browser è obsoleto.",
lead: "WP BrowserUpdate aiuta i siti WordPress ad avvisare quando un browser è troppo vecchio per l'esperienza prevista.",
support: "I gestori del sito definiscono la soglia di supporto. Il plugin gestisce avviso, testi e file visibili ai visitatori dal sito WordPress."
},
preview: {
title: "Il tuo browser non è aggiornato.",
body: "Aggiorna il browser per usare questo sito in modo sicuro e comodo.",
action: "Aggiorna il browser"
},
story: {
title: "Parti dal visitatore, non dall'elenco dei browser.",
lead: "La situazione è semplice: qualcuno apre un sito con un browser obsoleto e il sito offre un passo utile senza disturbare tutti gli altri.",
detect: {
title: "Verifica il supporto browser",
body: "Le versioni di browser supportate possono essere definite con valori predefiniti, versioni principali o versioni puntuali complete."
},
explain: {
title: "Spiega il problema",
body: "Un breve avviso può usare testi e link di download specifici del sito, senza lasciare il visitatore nel dubbio."
},
stay: {
title: "Resta in WordPress",
body: "L'avviso si configura da una normale schermata impostazioni e può essere testato prima del traffico reale."
}
},
runtime: {
title: "Pensato per evitare sorprese inutili nel frontend.",
lead: "Il runtime dell'avviso browser è incluso nel plugin, quindi la pagina pubblica non deve caricare script o stili da browser-update.org.",
sameOrigin: "I file runtime visibili ai visitatori vengono caricati dal sito WordPress.",
csp: "Questo è più adatto a Content Security Policy strette e blocchi anti-tracker.",
credit: "browser-update.org resta indicato come progetto upstream del runtime di notifica."
},
configure: {
title: "Controlla l'avviso senza trasformarlo in un progetto.",
lead: "La maggior parte dei siti può mantenere i valori predefiniti. Quando serve più precisione, le impostazioni restano comprensibili.",
thresholds: {
title: "Soglie browser",
body: "Sono supportati valori predefiniti, versioni principali, offset negativi e versioni puntuali come 137.0.3912.63."
},
targets: {
title: "Target separati",
body: "Edge moderno, Internet Explorer legacy e altri browser possono essere gestiti separatamente."
},
reminders: {
title: "Ritmo dei promemoria",
body: "Il ritmo dei promemoria controlla quando un avviso chiuso può riapparire allo stesso visitatore."
},
appearance: {
title: "Testi e aspetto",
body: "Testi, link e CSS del sito possono essere adattati senza stampare blocchi di stile grezzi nella pagina."
},
admin: {
title: "Schermata admin nativa",
body: "La configurazione resta in una pagina impostazioni WordPress mirata, non in una dashboard separata."
},
testing: {
title: "Test sicuro",
body: "La modalità test può forzare l'avviso durante la configurazione e viene disattivata prima del traffico reale."
}
},
links: {
title: "Installa da WordPress.org. Controlla il sorgente su GitHub.",
lead: "Per installazione e aggiornamenti normali usa la directory dei plugin. Per revisione del codice e issue usa il repository GitHub.",
wordpress: "Apri WordPress.org",
github: "Apri GitHub",
note: "Richiede WordPress 6.0 o successivo e PHP 7.4 o successivo."
},
footer: {
navAria: "Footer",
top: "Torna su",
wordpress: "WordPress.org",
github: "GitHub"
}
},
"es-ES": {
meta: {
title: "WP BrowserUpdate para WordPress",
description: "WP BrowserUpdate ayuda a los sitios WordPress a mostrar avisos claros a visitantes con navegadores obsoletos.",
ogDescription: "Un plugin de WordPress centrado en avisos de actualización del navegador, archivos runtime locales y ajustes prácticos."
},
skip: "Saltar al contenido",
language: {
navAria: "Elegir idioma"
},
theme: {
labelAuto: "Auto",
labelLight: "Claro",
labelDark: "Oscuro",
ariaAuto: "Tema: automático",
ariaLight: "Tema: claro",
ariaDark: "Tema: oscuro"
},
hero: {
title: "Un aviso claro cuando un navegador está obsoleto.",
lead: "WP BrowserUpdate ayuda a los sitios WordPress a avisar cuando un navegador es demasiado antiguo para la experiencia prevista.",
support: "Los responsables del sitio definen el umbral de compatibilidad. El plugin gestiona el aviso, los textos y los archivos visibles desde el sitio WordPress."
},
preview: {
title: "Tu navegador está desactualizado.",
body: "Actualiza tu navegador para usar este sitio de forma segura y cómoda.",
action: "Actualizar navegador"
},
story: {
title: "Empieza por la persona visitante, no por la lista de navegadores.",
lead: "La situación es sencilla: alguien abre un sitio con un navegador antiguo y el sitio ofrece un siguiente paso útil sin molestar al resto.",
detect: {
title: "Comprobar compatibilidad",
body: "Las versiones de navegador admitidas pueden definirse con valores por defecto, versiones principales o versiones exactas."
},
explain: {
title: "Explica el problema",
body: "Un aviso breve puede usar textos y destinos de descarga propios del sitio, para que la persona no tenga que adivinar."
},
stay: {
title: "Quédate en WordPress",
body: "El aviso se configura desde una pantalla normal de ajustes y puede probarse antes del tráfico real."
}
},
runtime: {
title: "Preparado para evitar sorpresas innecesarias en el frontend.",
lead: "El runtime del aviso está incluido en el plugin, por lo que la página pública no necesita cargar scripts ni estilos desde browser-update.org.",
sameOrigin: "Los archivos runtime visibles para visitantes se cargan desde el sitio WordPress.",
csp: "Esto encaja mejor con Content Security Policies estrictas y bloqueadores de rastreadores.",
credit: "browser-update.org sigue acreditado como proyecto upstream del runtime de notificación."
},
configure: {
title: "Controla el aviso sin convertirlo en un proyecto.",
lead: "La mayoría de los sitios pueden conservar los valores por defecto. Cuando hace falta más precisión, los ajustes siguen siendo comprensibles.",
thresholds: {
title: "Umbrales de navegador",
body: "Se admiten valores por defecto, versiones principales, offsets negativos y versiones exactas como 137.0.3912.63."
},
targets: {
title: "Destinos separados",
body: "Edge moderno, Internet Explorer heredado y otros navegadores pueden gestionarse por separado."
},
reminders: {
title: "Ritmo de recordatorio",
body: "El ritmo de recordatorio controla cuándo puede volver a aparecer un aviso cerrado para la misma persona."
},
appearance: {
title: "Texto y apariencia",
body: "Los textos, enlaces y CSS del sitio pueden ajustarse sin imprimir bloques de estilo sin procesar en la página."
},
admin: {
title: "Pantalla nativa de administración",
body: "La configuración permanece en una página de ajustes de WordPress enfocada, no en un panel separado."
},
testing: {
title: "Pruebas seguras",
body: "El modo de prueba puede forzar el aviso durante la configuración y se desactiva antes del tráfico real."
}
},
links: {
title: "Instala desde WordPress.org. Revisa el código en GitHub.",
lead: "Para instalación y actualizaciones normales usa el directorio de plugins. Para revisar el código e informar issues usa el repositorio de GitHub.",
wordpress: "Abrir WordPress.org",
github: "Abrir GitHub",
note: "Requiere WordPress 6.0 o superior y PHP 7.4 o superior."
},
footer: {
navAria: "Pie de página",
top: "Volver arriba",
wordpress: "WordPress.org",
github: "GitHub"
}
},
"fr-FR": {
meta: {
title: "WP BrowserUpdate pour WordPress",
description: "WP BrowserUpdate aide les sites WordPress à afficher des messages clairs aux visiteurs utilisant un navigateur obsolète.",
ogDescription: "Un plugin WordPress ciblé pour les messages de mise à jour du navigateur, les fichiers runtime locaux et des réglages pratiques."
},
skip: "Aller au contenu",
language: {
navAria: "Choisir la langue"
},
theme: {
labelAuto: "Auto",
labelLight: "Clair",
labelDark: "Sombre",
ariaAuto: "Thème : automatique",
ariaLight: "Thème : clair",
ariaDark: "Thème : sombre"
},
hero: {
title: "Un message clair quand un navigateur est trop ancien.",
lead: "WP BrowserUpdate aide les sites WordPress à prévenir les personnes lorsqu'un navigateur est trop ancien pour l'expérience prévue.",
support: "Les responsables du site définissent le seuil de prise en charge. Le plugin gère le message, les textes et les fichiers visibles depuis le site WordPress."
},
preview: {
title: "Votre navigateur est obsolète.",
body: "Veuillez mettre à jour votre navigateur pour utiliser ce site de manière sûre et confortable.",
action: "Mettre à jour le navigateur"
},
story: {
title: "Commencez par le visiteur, pas par la liste des navigateurs.",
lead: "La situation est simple : quelqu'un ouvre un site avec un navigateur ancien, et le site propose une suite utile sans gêner les autres visiteurs.",
detect: {
title: "Vérifier la prise en charge",
body: "Les versions de navigateurs prises en charge peuvent être définies avec des valeurs par défaut, des versions majeures ou des versions exactes."
},
explain: {
title: "Expliquer le problème",
body: "Un court message peut utiliser des textes et des liens de téléchargement propres au site, afin de ne pas laisser les visiteurs deviner."
},
stay: {
title: "Rester dans WordPress",
body: "Le message se configure depuis un écran de réglages classique et peut être testé avant le trafic réel."
}
},
runtime: {
title: "Conçu pour éviter les surprises inutiles côté frontend.",
lead: "Le runtime du message navigateur est inclus dans le plugin. La page publique n'a donc pas besoin de charger les scripts ou les styles depuis browser-update.org.",
sameOrigin: "Les fichiers runtime visibles par les visiteurs se chargent depuis le site WordPress.",
csp: "C'est plus adapté aux Content Security Policies strictes et aux bloqueurs de trackers.",
credit: "browser-update.org reste crédité comme projet upstream du runtime de notification."
},
configure: {
title: "Contrôler le message sans en faire un projet.",
lead: "La plupart des sites peuvent garder les réglages par défaut. Quand plus de précision est nécessaire, les réglages restent centrés sur des décisions compréhensibles.",
thresholds: {
title: "Seuils de navigateurs",
body: "Les valeurs par défaut, les versions majeures, les décalages négatifs et les versions exactes comme 137.0.3912.63 sont pris en charge."
},
targets: {
title: "Cibles séparées",
body: "Edge moderne, l'ancien Internet Explorer et d'autres navigateurs peuvent être gérés séparément."
},
reminders: {
title: "Rythme de rappel",
body: "Le rythme de rappel contrôle quand un message fermé peut réapparaître pour le même visiteur."
},
appearance: {
title: "Texte et apparence",
body: "Les textes, les liens et le CSS du site peuvent être ajustés sans imprimer de blocs de style bruts dans la page."
},
admin: {
title: "Écran d'administration natif",
body: "La configuration reste dans une page de réglages WordPress ciblée, pas dans un tableau de bord séparé."
},
testing: {
title: "Tests maîtrisés",
body: "Le mode test peut forcer l'affichage pendant la configuration et se désactive avant le vrai trafic."
}
},
links: {
title: "Installer depuis WordPress.org. Relire le code sur GitHub.",
lead: "Pour l'installation et les mises à jour normales, utilisez le répertoire des plugins. Pour relire le code et suivre les issues, utilisez le dépôt GitHub.",
wordpress: "Ouvrir WordPress.org",
github: "Ouvrir GitHub",
note: "Nécessite WordPress 6.0 ou plus récent et PHP 7.4 ou plus récent."
},
footer: {
navAria: "Pied de page",
top: "Retour en haut",
wordpress: "WordPress.org",
github: "GitHub"
}
}
};

function normaliseLocale(locale) {
return String(locale || "").replace("_", "-");
}

function localeFromLanguage(candidate) {
const normalised = normaliseLocale(candidate);
if (SUPPORTED_LOCALES.includes(normalised)) {
return normalised;
}
const language = normalised.slice(0, 2).toLowerCase();
if (Object.prototype.hasOwnProperty.call(LANGUAGE_PRIMARY, language)) {
return LANGUAGE_PRIMARY[language];
}
return null;
}

function initialLocale() {
const stored = localeFromLanguage(localStorage.getItem(LOCALE_STORAGE_KEY));
if (stored) {
return stored;
}
const browserLocales = navigator.languages && navigator.languages.length ? navigator.languages : [navigator.language];
for (const candidate of browserLocales) {
const matched = localeFromLanguage(candidate);
if (matched) {
return matched;
}
}
return "en-US";
}

function getPath(source, path) {
return path.split(".").reduce((value, key) => {
if (value && Object.prototype.hasOwnProperty.call(value, key)) {
return value[key];
}
return undefined;
}, source);
}

function validTheme(mode) {
return THEME_MODES.includes(mode) ? mode : "auto";
}

function resolveTheme(mode) {
if (mode === "light" || mode === "dark") {
return mode;
}
return systemTheme.matches ? "dark" : "light";
}

function currentLocale() {
return document.documentElement.lang && LOCALES[document.documentElement.lang] ? document.documentElement.lang : "en-US";
}

function applyTheme(mode, persist = true) {
const selectedMode = validTheme(mode);
const resolvedMode = resolveTheme(selectedMode);
const root = document.documentElement;
const strings = LOCALES[currentLocale()].theme;
root.dataset.theme = selectedMode;
root.dataset.resolvedTheme = resolvedMode;
if (persist) {
localStorage.setItem(THEME_STORAGE_KEY, selectedMode);
}
const button = document.querySelector("[data-theme-toggle]");
const label = document.querySelector("[data-theme-label]");
if (!button || !label) {
return;
}
const labelKey = `label${selectedMode.charAt(0).toUpperCase()}${selectedMode.slice(1)}`;
const ariaKey = `aria${selectedMode.charAt(0).toUpperCase()}${selectedMode.slice(1)}`;
label.textContent = strings[labelKey];
button.setAttribute("aria-label", strings[ariaKey]);
}

function applyLocale(locale) {
const selectedLocale = LOCALES[locale] ? locale : "en-US";
const strings = LOCALES[selectedLocale];
document.documentElement.lang = selectedLocale;
document.body.dataset.locale = selectedLocale;
document.title = strings.meta.title;
const description = document.querySelector('meta[name="description"]');
if (description) {
description.setAttribute("content", strings.meta.description);
}
const ogTitle = document.querySelector('meta[property="og:title"]');
if (ogTitle) {
ogTitle.setAttribute("content", strings.meta.title);
}
const ogDescription = document.querySelector('meta[property="og:description"]');
if (ogDescription) {
ogDescription.setAttribute("content", strings.meta.ogDescription);
}
const twitterTitle = document.querySelector('meta[name="twitter:title"]');
if (twitterTitle) {
twitterTitle.setAttribute("content", strings.meta.title);
}
const twitterDescription = document.querySelector('meta[name="twitter:description"]');
if (twitterDescription) {
twitterDescription.setAttribute("content", strings.meta.ogDescription);
}
document.querySelectorAll("[data-i18n]").forEach((element) => {
const value = getPath(strings, element.dataset.i18n);
if (typeof value === "string") {
element.textContent = value;
}
});
document.querySelectorAll("[data-i18n-attr]").forEach((element) => {
element.dataset.i18nAttr.split(",").forEach((entry) => {
const [attribute, path] = entry.split(":");
const value = getPath(strings, path);
if (attribute && typeof value === "string") {
element.setAttribute(attribute, value);
}
});
});
document.querySelectorAll("[data-locale]").forEach((button) => {
const active = button.dataset.locale === selectedLocale;
button.setAttribute("aria-pressed", active ? "true" : "false");
});
applyTheme(validTheme(localStorage.getItem(THEME_STORAGE_KEY)), false);
}

function setupLanguageButtons() {
document.querySelectorAll("[data-locale]").forEach((button) => {
button.addEventListener("click", () => {
const locale = localeFromLanguage(button.dataset.locale) || "en-US";
localStorage.setItem(LOCALE_STORAGE_KEY, locale);
applyLocale(locale);
});
});
}

function setupThemeButton() {
const button = document.querySelector("[data-theme-toggle]");
if (!button) {
return;
}
button.addEventListener("click", () => {
const current = validTheme(document.documentElement.dataset.theme);
const nextIndex = (THEME_MODES.indexOf(current) + 1) % THEME_MODES.length;
applyTheme(THEME_MODES[nextIndex]);
});
systemTheme.addEventListener("change", () => {
if (validTheme(document.documentElement.dataset.theme) === "auto") {
applyTheme("auto", false);
}
});
}

function setupSmoothScrollTop() {
const link = document.querySelector("[data-scroll-top]");
const target = document.getElementById("top");
if (!link || !target) {
return;
}
link.addEventListener("click", (event) => {
if (reducedMotion.matches) {
return;
}
event.preventDefault();
target.scrollIntoView({ behavior: "smooth", block: "start" });
history.replaceState(null, "", `${location.pathname}${location.search}`);
});
}

setupLanguageButtons();
setupThemeButton();
setupSmoothScrollTop();
applyLocale(initialLocale());
applyTheme(validTheme(localStorage.getItem(THEME_STORAGE_KEY)), false);
