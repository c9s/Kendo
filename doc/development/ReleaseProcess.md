Release Process
===============


The Problem
-----------

Depends on the git release flow, we added some special branch to improve 
this flow.

there are still some flow problems for developing many websites in parallel, 
because some requirements are for specific website, some don't.

We usually do:

    1. create new website
    2. clone the phifty with develop branch
    3. hack, hack, hack
    4. branch site/{site name} (phifty)
    5. commit to site/{site name} or develop branch
    6. till website is ready

To improve phifty we usually do:

    1. hack on develop branch
    2. if tested, then merge it to master branch

So we will have problems if:

    1. if A hacks on phifty on develop branch that changes behaviors for Y website,
       if B update phifty on developfor X website, A will get unstable changes that is not compatible
        for X website.

Branches
--------
* master 

    stable version, for production, every release to master should be tagged.
    web sites should also tagged with the production version.

    hotfix can be merged to master or develop branch to fix production problems.

* develop

    developing branch, small features on this branch. 

    feature/improvement branches is forked from this branch.

* feature/{feature name}

    feature branch is forked from develop branch, 
    and should be merged to rc branch after tested.

* rc-{version name}, rc-{version}/{site name}

    rc branch, release candidate branch.

    when features/requirements are for specific website, 
    the rc branch should be named rc-{version}/{site name}

    {version} is the new release version, when the rc branch is forked
    should remeber to bump phifty version.

    to release a rc branch:

    - check version (if the version is not correspond to the rc version, then bump it)
    - merge feature/improvement/bug branches that are planed to be released.
    - run tests
    - document change logs (from feature/improvement branches)
    - merge rc into master.
    - tag master with version and document release date.

* site/{site name}

    Special requirements (feature/improvement) for a website.

    This branch is like develop branch, and should be forked from develop branch.

