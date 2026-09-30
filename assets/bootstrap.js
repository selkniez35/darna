import { startStimulusApp } from 'vite-plugin-symfony/stimulus/helpers';
import MenuController from './controllers/menu_controller.js';

const app = startStimulusApp();
// register any custom, 3rd party controllers here
app.register('menu', MenuController);
