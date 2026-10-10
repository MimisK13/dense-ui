export function focusable(root) {
    return [...root.querySelectorAll(
        'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
    )].filter((element) => !element.hasAttribute('hidden'));
}

export function focusFirst(root) {
    focusable(root)[0]?.focus();
}

export function restoreFocus(element) {
    element?.focus?.();
}
