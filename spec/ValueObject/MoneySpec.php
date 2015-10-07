<?php

use \Catalog\ValueObject\Money;
use \Catalog\Exception\ValueObject\UnknownCurrencyCode;
use \Catalog\Exception\ValueObject\NonNumericValue;

describe('Money', function() {
    it('кидает исключение, если передана несуществующая валюта', function () {
        expect(function () { new Money(1, 'RUR'); })
            ->toThrow(new UnknownCurrencyCode('RUR'));
    });
    it('кидает исключение, если переданная сумма не является числом', function () {
        expect(function () { new Money('seven', 'RUB'); })
            ->toThrow(new NonNumericValue('seven'));
    });
    context('getters', function() {
        beforeEach(function() {
            $this->money = new Money(5, 'USD');
        });

        it('getCurrency возвращает валюту', function () {
            expect($this->money->getCurrency())->toBe('USD');
        });

        it('getValue возвращает сумму', function () {
            expect($this->money->getValue())->toBe(5);
        });
    });

});