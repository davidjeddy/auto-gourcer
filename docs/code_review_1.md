$host should probably be just an array or just a string. It has too many types, which will lead to a lot of type checking. It's easier if you just have whatever sets the host be type aware and deal with it that way.

 - FIXED

$repoData is also multiple types which leads to the same issues as $host. There are also several things setting $repoData directly, rather than going through a setter of some sort. Careful programming could mean this isn't an issue, but this could be completely avoided.

 - Discussed, switching to get/set methods for class properties.

I already mentioned this, but figured I would again for good measure. I prefer to see use statements rather than full namespace paths.

 - FIXED

Are you sure you want to force people to use env vars? While I understand it makes things easy, it would probably be better to go the proper dep injection route, whether it's through the constructor or setters/method injection.

 - YES

On that note, it's a shame that Dotenv doesn't have a static method. Are you opposed to (new Dotenv($this->basePath))->load()?

 - Static methods are considered a messing construct per PHPMD. Trying to avoid them personally as it allows for unintended data mutation

I prefer not to see echo anywhere in here. Some sort of output handler, sure, but direct echo pretty much ties somebody's hands on how to use this. I do think the output handler of whatever sort should be passed into the constructor as a depdency.

 - AGREED. Added Logging feature as part of 0.3.5

I would probably extract the $configArray foreach into its own method.

 - AGREED, will be replaced by get/set paradigm as per item 2

You could probably extract // dependent classes and the following lines into a method that makes the comment redundant or not necessary.

 - HUA?

I'm not sure what to say about $this->host = getenv(). On one hand, it feels redundant if they pass it through $configArray and also makes the echo incorrect. From what I can tell, there's no logic preventing this. However, I also realize you need to set a host initially even if you override it with $configArray. At the very least, put the output handler after the $configArray foreach.

 - FIXED

Is $repoCount actually going to be greater than 1 in any case? If not, it makes the for loop in run() a little weird.

 - YES, if passed as part of the configArray. The idea is the user can set how many repo's per day get rendered.

What happens if $host is not bitbucket? It looks like the only consequence is that $repoUrl doesn't get set, in which case clone and checkout are still attempted. This seems inefficient to me.

 - Later pre 1.x release versions will add other major repository providers.

I see multiple calls to $this->repoData[$i]['slug']. I normally like assigning that to a temp var, that way if it changes you only change it in one place.

 - FIXED

I'm not sure how this Gource stuff works, but is there a case where it's rendered before you call it? I'm just curious why you need to guard it with a conditional. I would probably extract that if block into its own method renderIfNotRendered or something.

 - FIXED

getRepoList() has the same check for the host being bitbucket. I'm seeing a pattern here.

 - See prev. response about multiple hosts

This is more of a note than anything else, but in every method except jsonDecode() you are using the if statement for the expected case, rather than the exceptional case.

 - VALID point. Im a bit defensive when it comes to execution continuation.

The comments don't really add much value. There are some you could extra the lines into a method with a descriptive name and make the comment redundant/unecessary, and there are some that don't really contribute any new information.

 - FIXED

checkout() in Git - all of that branch parsing, following those lines of code is a little rough to reason about, I think extracting that into a method with a good name could really benefit, here.

 - FIXED

That @source annotation is the type of comment I like to see. However, to nitpick, it would be nice to see that gist forked into your account, that way if the initial source deletes it you won't lose it

 - FIXED

An example of a comment that doesn't add any value is // exec git command - reading the code makes that clear

 - FIXED

() - I'd prefer for this to only return a bool, and let something else handle the output.

 - FIXED, method returns bool. echo output will be moved one logging library is added.

How does $dryRun end up true? Setting it after instantiating Gource? If so, I'd prefer to see that through some sort of setter method

 - AGREED, will add get/set methods for Gource and Git classes when added to AutoGourcer.

The end of render() - you return true regardless, shouldn't you return false in the if statement right above return true?

 - FIXED

And for the question you know I'm gonna ask? Where be the tests? 😄

 - Touche sir. touche. Adding init test suite as of 0.1.75. Added to road map
