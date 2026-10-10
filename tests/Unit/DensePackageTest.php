<?php

use Ui\Dense\DenseServiceProvider;

it('contains the distributable Dense assets', function (): void {
    expect(is_file(DenseServiceProvider::assetPath('dense.css')))->toBeTrue()
        ->and(is_file(DenseServiceProvider::assetPath('dense.js')))->toBeTrue();
});
