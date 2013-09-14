CurrentUser
===========
Phifty Framework provides CurrentUser concept,

With CurrentUser you can control basic permissions very
easily.

There are 3 components:

- CurrentUser Service
- CurrentUser Class
- CurrentUser Model


CurrentUser Service
-------------------
Phifty provides CurrentUser service, basically it's a
current user factory, simply loads framework config file
create the corresponding current user object, and record
object.

So in anywhere in the web application, you can get the 
current user object from CurrentUser service, for example,
in controller, we can write:

    $currentUser = kernel()->currentUser;

and in Twig template, we can write:

    {% set user = Kernel.currentUser %}
    {{ Kernel.currentUser }}

CurrentUser service reads config from framework.yml,
please remember to provide a basic current user service
config in framework.yml, eg:

  Services:
    CurrentUserService:
      Class: Phifty\Security\CurrentUser
      Model: User\Model\User

CurrentUser Class
-----------------
CurrentUser class is customizable, you can define your own 
current user class by inheriting the original current user
class that phifty already defined.

CurrentUser class provides a general interface for
registgering session from records, refresh user session
or logout.

CurrentUser class uses the mix-in technique to mix-in user 
record model, which provides `__get`, `__set` for
setting/getting user data like `role`,`id`... etc.

for example,

<?php
    $userRole = kernel()->currentUser->role;

    kernel()->currentUser->updateSession();

    kernel()->currentUser->setRecord($userRecord);
?>

After updating your current user record,
you should remember to refresh/update session data 
from the current record by using `updateSession` method
or using `setRecord` method to replace with anthoer user
record data.

CurrentUser Model
-----------------
You can also customize your current user model by defining
your own user model. remember to update the user model class
in framework.yml, CurrentUser Service reads this config to 
create/load current user object.



Usage
------
To login a user:

        $user = new \User\Model\User;
        $ret = $user->find(array( 'account' => $account ) );
        if( $ret->success 
            && $user->validatePassword('123123') ) 
        {
            kernel()->currentUser->setRecord( $user );
        }

To logout:

    kernel()->currentUser->logout();

To check permission by checking user role:

    $cuser = kernel()->currentUser;
    if( $user->role == 'admin' ) {

    }

To update user profile:

And because currentUser caches user record data in session,
thus we need to update session manually if we've update
record.

    $record = kernel()->currentUser->getRecord();
    if( $record->update(array( 'name' => 'New Name' ))->success )  {
        kernel()->currentUser->updateSession();
    }


In ActionKit
------------

In ActionKit, we call currentUserCan method to check
permissions.


In Model
--------

(to be continued)

