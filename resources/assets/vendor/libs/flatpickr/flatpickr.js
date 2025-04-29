import flatpickr from "flatpickr";
import { Indonesian } from "flatpickr/dist/l10n/id.js";

try {
    window.flatpickr = flatpickr
    window.Indonesian = Indonesian
} catch (e) {}

export { flatpickr, Indonesian }
