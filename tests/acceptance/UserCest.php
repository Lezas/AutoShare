<?php


class UserCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('CARS');
        $I->click('Prisijungti');
        $I->amOnPage('/login');
        $I->fillField('_username', 'test');
        $I->fillField('_password', '123456');
        $I->click('_submit');
        $I->amOnPage('/');
        $I->canSeeInSource('Atsijungti');
        $I->see('CARS');
    }
}
