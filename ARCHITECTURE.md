# Dense UI interactive architecture

## Responsibility boundary

```text
Blade    -> semantic server-rendered structure
CSS      -> Dense visual language and state styling
Alpine   -> local ephemeral interaction state
Livewire -> server state, search, validation, and re-rendering
Actions  -> application/domain behavior
```

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
`dialog`, `dropdown`, and `tabs` are reusable behaviors ready for Blade
wrappers. The behavior owns only UI state and keyboard/focus interaction;
application code owns queries, authorization, and domain validation.

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
