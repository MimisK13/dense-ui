<div
    x-data="denseConfirmModal()"
    x-cloak
    data-testid="dense-confirm-modal"
>
    <div
        class="dense-modal-backdrop"
        x-show="open"
        x-on:click.self="cancel()"
        x-on:keydown.escape.window="cancel()"
        role="presentation"
    >
        <div
            class="dense-confirm-modal"
            x-ref="dialog"
            x-trap.noscroll="open"
            role="dialog"
            aria-modal="true"
            aria-labelledby="dense-confirm-title"
            aria-describedby="dense-confirm-message"
        >
            <div class="dense-confirm-body">
                <h2 id="dense-confirm-title" class="dense-confirm-title">{{ __('Confirm') }}</h2>
                <p id="dense-confirm-message" class="dense-confirm-message" x-text="message"></p>

                <div class="dense-confirm-actions">
                    <x-dense::button variant="ghost" size="sm" x-on:click="cancel()">
                        {{ __('Cancel') }}
                    </x-dense::button>
                    <button
                        type="button"
                        class="button button-sm"
                        x-bind:class="danger ? 'button-danger' : 'button-primary'"
                        x-ref="confirmButton"
                        x-on:click="confirm()"
                        data-testid="dense-confirm-submit"
                    >
                        {{ __('Confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
