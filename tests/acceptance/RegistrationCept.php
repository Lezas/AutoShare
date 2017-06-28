<?php 
$I = new AcceptanceTester($scenario);
$I->amOnPage('/');
$I->see('CARS');
$I->click('Registracija');
$I->amOnPage('/register');
$I->fillField('fos_user_registration_form[username]', 'test');
$I->fillField('fos_user_registration_form[email]', 'test@test.te');
$I->fillField('fos_user_registration_form[plainPassword][first]', 'helloRat');
$I->fillField('fos_user_registration_form[plainPassword][second]', 'helloRat');
$I->click('Atnaujinti');
$I->amOnPage('/register/confirmed');
$I->see('Sveikiname, jūsų paskyra aktyvuota');
