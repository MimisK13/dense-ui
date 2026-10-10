import '../css/dense.css';
import { denseCombobox } from './components/combobox';
import { denseDialog } from './components/dialog';
import { denseDropdown } from './components/dropdown';
import { denseTabs } from './components/tabs';

let registered = false;

export function registerDense(Alpine) {
    if (!Alpine || registered) return;

    // Dense registers against the host Alpine runtime. It must never start a
    // second Alpine instance because Livewire owns Alpine initialization.
    Alpine.data('denseCombobox', denseCombobox);
    Alpine.data('denseDialog', denseDialog);
    Alpine.data('denseDropdown', denseDropdown);
    Alpine.data('denseTabs', denseTabs);
    registered = true;
}

if (typeof window !== 'undefined') {
    if (window.Alpine) {
        registerDense(window.Alpine);
    } else {
        document.addEventListener('alpine:init', () => registerDense(window.Alpine), { once: true });
    }
}
