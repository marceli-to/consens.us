import './bootstrap';

import flatpickr from 'flatpickr';
import { German } from 'flatpickr/dist/l10n/de.js';

flatpickr.localize(German);

window.flatpickr = flatpickr;
