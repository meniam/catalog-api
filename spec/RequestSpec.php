<?php

use Catalog\Request;
use kahlan\plugin\Stub;

describe('Request', function() {
    beforeEach(function() {
        $this->subject = new Request(new \Buzz\Browser(), ['a', 'b', 'c']);
    });

    context('->perform', function () {
        it('вызывает метод getRandomKey', function () {
            expect($this->subject)->toReceive('getRandomKey');
            $this->subject->prepare();
        });
        it('вызывает метод getRandomProxy', function () {
            expect($this->subject)->toReceive('getRandomProxy');
            $this->subject->prepare();
        });
    });

});