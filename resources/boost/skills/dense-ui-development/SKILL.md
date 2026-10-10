---
name: dense-ui-development
description: Use when developing, reviewing, or extending the `dense/ui` Laravel package, including Dense Blade components, package CSS, Alpine behaviors, accessibility, Livewire compatibility, asset delivery, or package tests.
---

# Dense UI package development

Use this skill for work inside `dense/ui`. The package is a
Laravel-first, domain-neutral UI system. Its public API is Blade; Alpine is
an internal enhancement layer for local interaction.

## Before changing code

1. Read the nearby component, its sibling components, and `ARCHITECTURE.md`.
2. Check `composer.json`, `package.json`, and `vite.config.js` for the
   installed contracts before relying on framework or build behavior.
3. Search for an existing component, icon, CSS token, or Alpine behavior
   before adding another one.
4. Keep the change inside the package. Application concepts such as Product,
   Asset, Container, StockLevel, Store, Opportunity, or warehouse rules do
   not belong here.

## Responsibility boundaries

Keep these boundaries explicit:

```text
Blade       semantic server-rendered structure and attribute forwarding
CSS         visual tokens, density, responsive layout, and state styling
Alpine      local open/closed, selection, keyboard, and focus state
Livewire    server state, search, validation, authorization, and rerenders
Application domain
            business rules and Actions
```

Alpine must never decide business eligibility, query a database, authorize a
user, or perform a stock/container operation. A Dense component may dispatch
or preserve `wire:*` attributes, but the server remains authoritative.

## Component conventions

- Add Blade components under `resources/views/components/<name>/index.blade.php`.
- Use the package namespace, for example `<x-dense::field>` or
  `<x-dense::combobox>`.
- Forward `class`, `id`, `wire:*`, `x-*`, `aria-*`, and `data-*` attributes
  unless there is a documented reason not to.
- Keep labels associated with controls and expose validation through a
  reusable field/error anatomy where appropriate.
- Put reusable icons under `resources/views/components/icons/` and consume
  them through the package icon component. Do not paste one-off SVG markup
  into pages or repeated components.
- Keep static primitives usable without Alpine. Do not add `x-data` merely
  for styling.
- Prefer semantic buttons, inputs, listboxes, dialogs, tabs, and landmarks.

Existing component families include static primitives such as `button`,
`input`, `field`, `label`, `select`, `table`, `table-card`, `card`, `badge`,
`status`, and `empty-state`, plus interactive components such as
`combobox`, `dialog`, `dropdown`, `tabs`, `side-panel`, `date-picker`,
`calendar`, `store-picker`, and `dropzone`. Confirm the current implementation
before assuming a component is fully centralized.

## Alpine architecture

The host application owns Alpine. Dense must not import Alpine as its runtime,
assign `window.Alpine`, or call `Alpine.start()`.

Add reusable behavior under `resources/js/components/` and shared utilities
under `resources/js/utilities/` only when the behavior is genuinely shared.
Register it through the single `registerDense(Alpine)` entry point in
`resources/js/index.js`; do not require consumers to change their bootstrap
when a new Dense behavior is added.

Use semantic state such as `aria-expanded`, `aria-selected`, `data-state`,
and `hidden`. Let CSS style state rather than making JavaScript know visual
tokens. Do not clone DOM with Alpine templates or rebuild Blade markup in the
browser.

Interactive behavior must cover the applicable keyboard and focus contract:

- Escape closes transient UI;
- outside interaction closes when the pattern requires it;
- focus moves to the initial target and returns to the trigger;
- arrow/Home/End navigation is used for list and tab semantics;
- disabled options cannot be selected;
- IDs and ARIA relationships remain valid after Livewire morphing.

Combobox behavior owns query-local interaction only: active option, keyboard
navigation, selection mechanics, focus, and ARIA. Livewire/application code
owns result retrieval, authorization, selected domain values, and validation.
Do not add `wire:ignore` unless a demonstrated morphing defect requires it.

## Livewire compatibility

Assume components can be rendered, morphed, and navigated without a full page
reload. Avoid global listeners and module side effects that accumulate after
Livewire updates or `wire:navigate`. If a global listener is necessary, make
registration idempotent and document its cleanup/lifecycle.

Preserve arbitrary Livewire attributes on Blade elements. Verify behavior
after a server update, not only on the first render, whenever an interactive
component changes.

## Dense visual language

Use the existing package CSS and tokens. Dense means high useful-information
density, not unreadably small text or arbitrary shrinking. Prefer consistent
control heights, compact label/control spacing, restrained borders, small
radii, and borders/dividers over decorative shadows. Shadows communicate
floating elevation only.

Do not introduce application-specific colors, typography, or layout systems
inside one component when a package token exists. Keep responsive behavior
mobile-safe and preserve visible labels, focus indication, contrast, and
touch usability.

## Assets and build

The package exposes compiled `dist/dense.css` and `dist/dense.js` through the
Laravel provider and the `@denseStyles` / `@denseScripts` directives. Source
changes belong in `resources/css` or `resources/js`; rebuild distribution
assets with the package Vite configuration. Do not hand-edit divergent `dist`
files.

The generated JavaScript must not embed Alpine or Livewire. The package must
remain installable through Composer without requiring a separate consumer npm
package or manual asset publishing for normal use.

## Testing and verification

For PHP/Blade changes, run the narrowest relevant Pest tests first, then:

```text
composer validate --no-check-publish --strict
vendor/bin/pest --compact
git diff --check
```

For JavaScript or CSS changes, also run the package production build and
inspect the generated assets. Test accessibility hooks and forwarded
attributes when they are part of the change. Prefer behavior assertions over
brittle pixel or implementation assertions.

Before committing, inspect the package repository status and stage only the
intended package files. Never include `vendor/`, `node_modules/`, secrets, or
application-domain changes.

## Adding a new interactive component

1. Confirm the behavior is generic and reusable across Laravel applications.
2. Add the server-rendered Blade component under the package component path.
3. Add a focused Alpine behavior only for local interaction state.
4. Register it through `registerDense(Alpine)`.
5. Add keyboard, focus, ARIA, Livewire attribute, and morphing coverage.
6. Add concise package documentation and update `ARCHITECTURE.md` when the
   public architecture changes.
7. Rebuild `dist/dense.js`/`dist/dense.css` when source assets changed.
8. Run Pest, the production build, and `git diff --check` before handoff.

Completion means the Blade API, responsibility boundary, accessibility
contract, Livewire behavior, built assets, and focused tests all agree with
the implementation.
