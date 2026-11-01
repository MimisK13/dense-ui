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

## Blade / Alpine component boundary

Dense is Blade-first. Reusable component markup belongs in Blade components,
not in browser-side JavaScript. Blade owns semantic HTML, slots, attribute
forwarding, component composition, labels, control relationships, and the
static ARIA structure required by the component.

Prefer a public API such as:

```blade
<x-dense::dropdown>
    ...
</x-dense::dropdown>
```

Application developers should primarily consume Blade components. Internal
`x-data` wiring is an implementation detail of the Blade component, not the
package's primary consumer API.

Reusable local interaction belongs in `Alpine.data()` modules registered by
the existing `registerDense(Alpine)` entry point. Alpine may own open/closed
state, highlighted options, keyboard navigation, focus movement, temporary
selection, overlay behavior, and other local ephemeral state. It must not own
the reusable HTML structure itself.

Dense does not use Alpine as a client-side component templating engine. Do not
use `innerHTML`, HTML-string injection, `cloneNode`, template cloning, or
manual client-side Blade-equivalent DOM construction for reusable Dense
components. Do not use `Alpine.initTree(...)` as normal component construction
or rehydration. It may be considered only for exceptional infrastructure
work with a documented reason, never as the default component pattern.

Blade already renders the component structure and Livewire owns server-rendered
DOM updates. Keeping those responsibilities separate protects morphing,
focus and state preservation, accessibility ownership, and security review.
Declarative Alpine state bindings may enhance markup rendered by Blade, but
they are not a replacement for the Blade component contract.

## Progressive enhancement and state ownership

Dense components should render meaningful semantic HTML before JavaScript
behavior is applied. JavaScript enhances interaction; it does not create the
fundamental component structure. This does not require every interactive
component to be fully functional without JavaScript, but it does require the
server-rendered structure, labels, controls, and relationships to be present
in Blade.

Livewire owns server/application state: database-backed values, queries,
search results, pagination, filtering, validation, permissions, and server
workflow state. Alpine owns only local ephemeral interaction. For example,
Alpine may manage a combobox's open state, active option, keyboard handling,
focus, and selection mechanics; Livewire owns the query, result dataset,
selected domain value, authorization, and server validation.

Business rules remain outside Alpine. Stock availability, lifecycle
transitions, eligibility, locking, permissions, financial calculations,
event-sourcing rules, and domain mutations belong to canonical application
Actions and server-side rules:

```text
Blade / Livewire / Volt
        -> canonical Action
        -> domain behavior
```

## Choosing Alpine.data versus inline x-data

Use a reusable `Alpine.data()` behavior when interaction has keyboard or
focus requirements, multiple state transitions, accessibility state,
lifecycle cleanup, or repeated use across components. Examples include
comboboxes, dialogs, dropdowns, tabs, popovers, and scanner-oriented UI.

Keep trivial one-off state inline when it is genuinely local and unlikely to
be reused, for example `x-data="{ open: false }"` for a single disclosure.
Do not create a module for every boolean toggle. The rule is:

```text
reusable interaction -> Alpine.data module
trivial one-off interaction -> inline Alpine is acceptable
```

## Livewire lifecycle contract

Every behavior must remain compatible with Livewire DOM morphing,
`wire:navigate`, and repeated mount/unmount. Prefer server-rendered Blade
markup, local Alpine state, idempotent registration, and explicit cleanup for
any necessary global listener. Do not rely on arbitrary timeouts, full-node
recreation, DOM cloning, or lifecycle workarounds that replace Livewire's DOM
ownership. Preserve IDs, ARIA relationships, focus behavior, and forwarded
`wire:*` attributes after morphing and navigation.

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
`status`, `empty-state`, and `avatar`, plus interactive components such as
`combobox`, `dialog`, `dropdown`, `tabs`, `side-panel`, `date-picker`,
`calendar`, `store-picker`, `dropzone`, and `confirm-modal`. Confirm the
current implementation before assuming a component is fully centralized.

## Alpine architecture

The host application owns Alpine. Dense must not import Alpine as its runtime,
assign `window.Alpine`, or call `Alpine.start()`.

Add reusable behavior under `resources/js/components/` and shared utilities
under `resources/js/utilities/` only when the behavior is genuinely shared.
Register it through the single `registerDense(Alpine)` entry point in
`resources/js/index.js`; do not require consumers to change their bootstrap
when a new Dense behavior is added.

The preferred shape is a small behavior factory whose state and methods are
local to the component:

```js
export function denseDropdown(config = {}) {
    return {
        open: false,

        init() {},
        toggle() {},
        close() {},
    };
}
```

The Blade component supplies the structure and wires this behavior internally;
the application does not need to know the factory name for ordinary use.

Use semantic state such as `aria-expanded`, `aria-selected`, `data-state`,
and `hidden`. Let CSS style state rather than making JavaScript know visual
tokens. Keep reusable markup in Blade and reusable behavior in
`Alpine.data()`; do not turn Alpine into a template engine.

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
2. Design and add the server-rendered Blade component under the package
   component path, including semantic structure, slots, attributes, labels,
   and static ARIA relationships.
3. Add a focused `Alpine.data()` behavior only for reusable local
   interaction state; keep the DOM structure in Blade.
4. Register it through `registerDense(Alpine)` without introducing a new
   consumer bootstrap path.
5. Add keyboard, focus, ARIA, Livewire attribute, and morphing coverage.
6. Add happy-path and meaningful unhappy-path tests.
7. Add concise package documentation and update `ARCHITECTURE.md` when the
   public architecture changes.
8. Verify `wire:navigate` and repeated mount/unmount behavior where relevant.
9. Rebuild `dist/dense.js`/`dist/dense.css` when source assets changed.
10. Run Pest, the production build, and `git diff --check` before handoff.

Completion means the Blade API, responsibility boundary, accessibility
contract, Livewire behavior, built assets, and focused tests all agree with
the implementation.

## Evaluating external patterns

External examples, blog posts, framework snippets, and third-party Alpine
patterns are reference material only. Before adopting one, compare it with
the Blade-first API, Livewire DOM ownership, Alpine lifecycle, accessibility,
and package maintenance cost. Do not copy an Alpine-only template pattern
when Blade already provides the reusable structure.
