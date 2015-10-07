<?php

class TestFreezable extends \Catalog\Freezable
{
    public function __construct()
    {
        $this->allowedFields = array_merge($this->allowedFields, ['x']);
    }
}

describe('Freezable', function () {
    beforeEach(function () {
        $this->object = new TestFreezable();
    });

    context('незамороженный объект', function () {
        it('ициализорованное значение соответствует установленному', function () {
            $this->object->x = 5;
            expect($this->object->x)->toBe(5);
        });
        it('неинициализорованное значение равно null', function () {
            expect($this->object->x)->toBeNull();
        });
        it('isset возвращает true для объявленных значений', function () {
            expect(isset($this->object->x))->toBeTruthy();
        });
        it('isset возвращает false для необъявленных значений', function () {
            expect(isset($this->object->y))->toBeFalsy();
        });
        it('isFrozen возвращает false', function () {
            expect($this->object->isFrozen())->toBeFalsy();
        });
    });

    context('замороженный объект', function () {
        beforeEach(function () {
            $this->object->freeze();
        });
        it('isFrozen возвращает true', function () {
            expect($this->object->isFrozen())->toBeTruthy();
        });
        it('изменение значения вызывает exception', function () {
            expect(function() { $this->object->x = 5; })->toThrow(new \Catalog\Exception\FrozenObjectChange());
        });
    });
});