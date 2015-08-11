<?php
namespace SimpleApp\User;
use Kendo\IdentifierProvider\ActorIdentifierProvider;

class AdminUser implements ActorIdentifierProvider
{
    public function getActorIdentifier()
    {
        return 'admin';
    }
}
