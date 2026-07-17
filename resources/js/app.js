import './bootstrap';
import {
    initFlowbite,
    Accordion,
    Carousel,
    Collapse,
    Dial,
    Dismiss,
    Drawer,
    Dropdown,
    Modal,
    Popover,
    Tabs,
    Tooltip,
    Datepicker,
    InputCounter,
} from 'flowbite';

// Versi CDN lama (dist/flowbite.min.js) menaruh semua class ini di window,
// jadi script inline di blade (mis. "new Modal(...)" di categories/index.blade.php)
// bisa langsung memakainya. Import lewat npm tidak otomatis melakukan itu,
// makanya kita expose manual di sini biar perilakunya sama persis.
window.initFlowbite = initFlowbite;
window.Accordion = Accordion;
window.Carousel = Carousel;
window.Collapse = Collapse;
window.Dial = Dial;
window.Dismiss = Dismiss;
window.Drawer = Drawer;
window.Dropdown = Dropdown;
window.Modal = Modal;
window.Popover = Popover;
window.Tabs = Tabs;
window.Tooltip = Tooltip;
window.Datepicker = Datepicker;
window.InputCounter = InputCounter;

initFlowbite();