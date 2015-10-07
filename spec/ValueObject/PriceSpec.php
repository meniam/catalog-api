<?php

use \Catalog\ValueObject\Price;
use \Catalog\Exception\ValueObject\NegativePrice;

describe('Price', function() {
    it('кидает исключение, если передана отрицательная сумма', function () {
        expect(function () { new Price(-1, 'USD'); })
            ->toThrow(new NegativePrice(-1));
    });
});