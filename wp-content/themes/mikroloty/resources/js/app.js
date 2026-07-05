import Alpine from 'alpinejs';

/**
 * Alpine.js — lekka interaktywność (m.in. filtr kadry po roku bez przeładowania).
 * Rejestrujemy globalnie, żeby komponenty Blade mogły używać atrybutów x-data itd.
 */
window.Alpine = Alpine;

Alpine.start();
