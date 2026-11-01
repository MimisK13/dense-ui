# Dense UI interactive architecture

## Responsibility boundary

```text
Blade    -> semantic server-rendered structure
CSS      -> Dense visual language and state styling
Alpine   -> local ephemeral interaction state
Livewire -> server state, search, validation, and re-rendering
Actions  -> application/domain behavior
```

Blade owns reusable markup: semantic HTML, slots, attributes, labels, form
relationships, and structural ARIA. Alpine owns reusable local interaction
through `Alpine.data()` modules. Livewire owns server/application state, and
application Actions own business rules and invariants.

The public API remains Blade-first:

```blade
<x-dense::combobox ... />
```

Consumers should not need to wire `x-data="denseCombobox(...)"` directly
unless they are extending the package itself.

Dense intentionally does not use Alpine as a reusable markup templating
engine. Do not inject or clone component markup with `innerHTML`, HTML
strings, `cloneNode`, or normal `Alpine.initTree(...)` construction. Blade
renders the component structure; Alpine enhances it with local behavior.
Declarative Alpine bindings may control state inside that Blade-rendered
structure, but they do not replace the Blade component contract.

Progressive enhancement means semantic server-rendered structure exists before
JavaScript behavior is applied. This preserves Livewire morphing, focus and
state stability, and clear accessibility ownership.

Dense does not own Alpine. Its package entry point exports
`registerDense(Alpine)`, and the built browser asset registers behaviors
against the Alpine instance supplied by the host application.

## Component inventory

Static primitives are `badge`, `button`, `button-group`, `empty-state`,
`field`, `field-label`, `fieldset`, `icon`, `icon-button`, `input`, `label`,
`page-header`, `radio`, `select`, `status`, `table`, `table-card`, `textarea`,
and `toolbar`. `avatar` is a static identity primitive. `card` and
`table-card` additionally expose local disclosure state when configured as
collapsible.

`combobox` uses the reusable `denseCombobox` behavior. `confirm-modal`,
`dialog`, `dropdown`, and `tabs` expose reusable Blade structure backed by
their package behaviors where interaction is required. Dropdown supports
explicit generic content or action-menu semantics and an optional floating
position for overflow containers. Tabs support the
`underline` and `plain` visual variants. The behavior owns only UI state and
keyboard/focus interaction; application code owns queries, authorization,
and domain validation.

The date picker, calendar, store picker, and dropzone still contain local
Alpine objects. They are observed candidates for independent migration, not
reasons to introduce a universal widget engine. Store behavior remains an
application compatibility concern rather than a generic Dense domain model.

## Loading and accessibility

`@denseStyles` and `@denseScripts` are Laravel directives registered by the
service provider. They serve the built files through package routes, append a
file-modification version query, use immutable cache headers, and avoid
duplicate output in one request. Consumers do not publish assets for normal
operation. The host must load the script before Alpine initialization.

Interactive components use semantic controls, preserve forwarded attributes,
expose ARIA state, support keyboard navigation and Escape, and restore focus
after overlays close. CSS owns visual tokens; JavaScript exposes behavior and
semantic state only.

Dynamic accessibility has split ownership: Blade provides semantic elements,
labels, IDs, and static ARIA relationships; Alpine updates state such as
`aria-expanded`, `aria-selected`, and `aria-activedescendant`, and manages
focus, Escape, and focus restoration.

New interactive components follow this path: design Blade-first markup, add a
domain-neutral `Alpine.data()` behavior only when reusable interaction is
needed, register it through `registerDense(Alpine)`, preserve accessibility
and Livewire morph/navigation behavior, add happy and meaningful unhappy-path
tests, update docs, and rebuild distribution assets when source assets change.
