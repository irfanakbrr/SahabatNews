import './bootstrap';

import Alpine from 'alpinejs';

// Import Bootstrap JS (Popper.js sudah otomatis dihandle oleh Bootstrap jika tersedia)
import 'bootstrap';

// Import Chart.js
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
window.Chart = Chart; // Membuat Chart.js tersedia secara global jika diperlukan di script inline

window.Alpine = Alpine;

Alpine.start();
