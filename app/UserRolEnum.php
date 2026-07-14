<?php

namespace App;

enum UserRolEnum: string
{
    case DENTISTA = 'dent';
    case RECEPCIONISTA = 'recep';
    case ADMINISTRADOR = 'admin';
}
