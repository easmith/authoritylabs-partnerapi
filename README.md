AuthorityLabs Partner API
=========================

    require_once("vendor/autoload.php");
    
    $al = new AuthorityLabs('my_token');
    
Account & System Status

    $al->accountInfo()
    
Setting Up Your Callback URI

    $al = new AuthorityLabs('my_token', 'callback');

Adding to the Immediate Queue

    $al->immediateQueue($keyword, $engine, $locale, $callback);
    
Adding to the Delayed Queue

    $al->delayedQueue($keyword, $engine, $locale, $callback);
    
Accessing Search Results Pages

    $al->getResult($keyword, $engine, $locale);
    
Supported Locales & Parameters

    $al->getSupported($engine);