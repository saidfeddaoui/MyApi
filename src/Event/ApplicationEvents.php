<?php

namespace App\Event;

final class ApplicationEvents
{
    const PHONE_REGISTRATION = 'phone.registration';
    const SUCCESS_LOGIN = 'success.login';
    const NEW_PRE_DECLARATION = 'new.pre_declaration';
    const REJECT_PRE_DECLARATION = 'reject.pre_declaration';
    const ACCEPT_PRE_DECLARATION = 'accept.pre_declaration';
}