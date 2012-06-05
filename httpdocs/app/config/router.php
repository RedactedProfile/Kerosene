<?
/**
Routes and You!

Allows you to specify transparent rewrites, basic format allows you to rewrite controllers and methods/pages like this:

$route['newURL'] = 'oldURL';
$route['newControler/newMethod'] = 'oldController/oldMethod';

real world
"articles" has been renamed to "news", without restructuring everything:
$route['news'] = 'articles';

now anytime someone visits the new url of: www.site.com/news/122322, it will behind the scenes really go to www.site.com/articles/122322. Meaning the only thing needed to be "recoded" is the links pointing to the new URL

methods segment operates much the same way, but requires a matching controller
lets say the old url was: www.site.com/articles/post_author/derrick_zoolander
and the new url is: www.site.com/articles/author/derrick_zoolander
$route['articles/author'] = 'articles/post_author';
and lets say the overall new url is: www.site.com/news/author/derrick_zoolander, we would need:
$route['news/author'] = 'articles/post_author';

**/
