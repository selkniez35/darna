import './bootstrap.js';
import './styles/app.css';
import '@hotwired/turbo';

// Rechargement à chaud de FrankenPHP : chargé uniquement quand le serveur l'active,
// sinon le script lève une erreur qui bloque tout le module (et donc le CSS en dev).
if (document.querySelector('meta[name="frankenphp-hot-reload:url"]')) {
    import('./hot-reload.js');
}
