# Contributing to wprsrv

*wprsrv* is available at Github. Contributions should be done there also.

## About these contribution guidelines

These guidelines may change over time. Be sure to keep track of the changes if you
want to contribute.

## Issues

Feel free to report all kinds of issues to the issue tracker. Please try to be as
specific as possible.

## Contributing code

### Requirements

-   Composer (PHP package manager)
-   npm (Node Package Manager)
-   Gulp (build tool)
-   Bower (Frontend package manager)
-   PHPUnit (PHP unit testing)

### Setting up the development version

1.  Create a fork for yourself
2.  Clone your fork to your development environment
3.  Add the official/main repo as upstream with `git remote add upstream <repo-uri>`
4.  Make sure you have all the requirements installed
5.  Install requirements, initialize the development version and run tests to see
    that everything works
    
        $ composer install
        $ npm install
        $ bower install
        $ gulp compile
        $ phpunit
        
6.  Activate the plugin and see whether it works
        
### Creating your features, fixes and changes

All work should be done on a feature branch to make separating features simpler.

>   "This plugin really should do ABC like that other plugin does. I'm going to
>   implement it for them!"
>
>       $ git checkout -b feature-abc

When working inside the feature branch you created, you can get changes from the main
repo by pulling the changes from the main repo's develop branch and then rebasing
your work ontop of it.

>   "Oh, there are some big changes available! Better get 'em before I've drifted too
>   far apart!"
>   
>       $ git checkout develop
>       $ git pull upstream develop
>       $ git checkout feature-abc
>       $ git rebase develop
        
If the feature or change is large, consider creating tests for it. *wprsrv* uses 
PHPUnit. You can then be at ease when someone says it does not work.

When you think your new shiny feature or fix or change is ready to be added to the 
main plugin repository, create a good-old pull request at Github. Begin by pushing
your feature branch to your Github fork of the plugin.

>   "Whew, now it works! Time to get it upstream for consideration!"
>
>       $ git push origin feature-abc
        
Browse to your fork's page at Github, open the feature branch and initiate a pull
request against the main repo's develop. Remember to rebase ontop of the main repo's
latest changes before sending a pull request to avoid unwanted surprises. Github has
an in-depth guide on pull requests if you want to know more.

If your feature, fix, change or whatever is considered helpful and usable, it can be
merged to the main repo.

#### Why the **** didn't you merge my pull request!?

Sorry, some contributions--while certainly made with good intentions and helpful
means in mind--are not needed. If the changes are out of scope for this plugin or
are not helpful in moving the plugin forward, then chances are that the contribution
will not be accepted.

Perhaps you created something that would work well as a plugin on its own? Perhaps
you didn't follow the code style well enough? Perhaps the fix was already implemented
by another contributor? Perhaps you clumped too many differrent changes to a single
pull request?

Helpful tip: before beginning work on a larger feature or change, create an issue to
the Github issue tracker and ask for opinions whether the feature would be a good
contribution.

Bottom-line: we do not turn down pull requests for no reason. If you feel like your
contribution should have been included, feel free to contact me/us to get some 
clarification as to why that happened.

## Translations

Translations are welcome. You can use the `languages/wprsrv.pot` file as the source
for your translations.

### Submitting translations

Sending translations can be done via the issue tracker. Translations are made
available at the documentation page with verified version compatibility to not keep
possibly broken translations inside the repository.

Languages that have 100% committed translators who can create per version
translations can be included in the repository. Each language may be removed from the
repository in case translations do not keep in pace with plugin version releases.

## Contributing by other means

If you do not want or know how to contribute using the above guidelines, you can
always just share the word, discuss issues at Github or send feedback in general.
