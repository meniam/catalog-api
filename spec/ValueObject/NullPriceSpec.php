<?php

use \Catalog\ValueObject\NullPrice;

describe('NullPrice', function() {
    beforeEach(function () {
        $this->price = new NullPrice();
    });
    it('getCurrency возвращает null', function () {
        expect($this->price->getCurrency())->toBeNull();
    });
    it('getValue возвращает null', function () {
        expect($this->price->getValue())->toBeNull();
    });
});