<?php
$I = new AcceptanceTester($scenario);
$I->amOnPage('/');
$I->see('2016 Tesla Model X');
$I->click('2016 Tesla Model X');
$I->amOnPage('/car/1');
$I->see('2016 Tesla model X');
$I->see('Gallery');