<?php
namespace SimpleApp\User;
use Kendo\IdentifierProvider\ActorIdentifierProvider;

class NormalUser implements ActorIdentifierProvider
{
    public function getActorIdentifier()
    {
        return 'user';
    }
}

