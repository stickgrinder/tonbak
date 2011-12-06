# Tonbak
**GetText based i18n/translation library and Spark for CodeIgniter 2 PHP Framework.**

This doc will be soon updated. What you can find here so far is the work *zarb* made kindly available [here](http://codeigniter.com/forums/viewthread/94922/#951403).
The original contributor didn't set up a public repo for this project and since he told himself happy with the idea of having it ported to a Spark, here is a development headquarter.

## Why "Tonbak"?

Tonbak is another name for [zarb](http://en.wikipedia.org/wiki/Tonbak), a well-known persian percussion instrument. I choose this name for following reasons:

- Since Zarb was the original contributor's nick, and tonbak is another word for zarb
- Since music is a universal language, so I thought it was nice to name this project after a musical instrument
- Since I'm a drummer, so choosing a percussion instrument sounded natural

## Installation

**Almost copypasted from CI2 forum post:**

1. Copy the file name Locale.php into your /application/libraries
1. Add  `locale` to your `/application/config/autoload.php`:
`$autoload[‘libraries’] = array(‘locale’);`
1. Create a `locales` folder inside you `/application/language` one and add subdirectories as follow:
<code>
<pre>
    application
      language
        locales
          en_EN
            LC_MESSAGES
          fr_FR
            LC_MESSAGES
          es_ES
            LC_MESSAGES
          ...
</pre>
</code>
(Repo provides examples files)

**You're done! :)**

Use `_()` function to translate strings in your application.
`<?php echo _('Translate my hello world!'); ?>`

## Usage

### Export strings

To manage .po and .mo files Zarb provided an additional controller: `po.php` which does pretty much everything for you.
Copy that controller into your application and visit
<code>
http://yourdomain.com/po/create_po
</code>
to have your source strings exported.

What po.php does is to scan your application folder and all sub directories, identify all .php files inside, create a .txt file which sums up all strings and finally create the .po file with all your translations for each languages available (define new languages adding related folders to `application/languages/locales`, such as `it\_IT' or 'en\_UK').

Opening `po.php` you can find `$send_po_mail` array; configure your email address here to receive updated .po files by mail.

Since `po.php` `create_po` action performs a merge with the old .po file, you can run it without overwriting already done translations.


### Put translations in place

You can use [Poedit](http://www.poedit.net/) to translate your files (or whatever you want), then put each file in proper folder:
<code>
<pre>
  /application/language/locales/en_EN/LC_MESSAGES/yourdomain.com.po
  /application/language/locales/es_ES/LC_MESSAGES/yourdomain.com.po
  /application/language/locales/fr_FR/LC_MESSAGES/yourdomain.com.po
</pre>
</code>

When the .po translated are in place, run again the `po.php` controller, this time calling `create_mo` action:
<code>
http://yourdomain.com/po/create\_mo
</code>

Congratulations! Website translated! :)

### Setting your domain name

So far you could find library's settings in Locale.php (rows 12-15) but I plan to move that stuff into a configuration file.
Settings are easy enough to understand for anybody understanding what I'm writing. :P


## Boons and things to come

### Using this stuff with Twig and Hydrant sparks/libraries

Since both Twig and H2O (can be found into Hydrant Spark/Lib) template engines rely on gettext for translations, you have to do pretty nothing else than load Twig or H2O i18n extensions. GetText domain is already set by Tonbak, so you don't even have to configure your environment, just load & use.

What's missing is the ability to extract strings from you twig/h2o templates and views. This will be added as additional sugar in the days to come.
So far you could rely on Twig and H2O extraction procedures, paying attention to what you do.

My advice: git translations! :) You've been warned.

### Configuration is ugly

Yep, I know! I'll move it in a config file ASAP.

### One more note

I've removed original Zarb's `words.php` controller and all pre-made "common words" translations, since I plan to redesign this stuff as kinda fixtures to be imported only when needed. You can find more at the original post (link above).

## Credits

- Zarb deserves all credits for the stuff you can find in this first commit. This is not yet perfect but it rocks as hell!
Unfortunately I cannot find his (her?) contacts if not his account on CI's forum (it seems it has been created just to contribute this baby, no more). His profile is not public available, so I don't have any clue on how to contact him, nor on other works and contributions.
I'd be glad to enhance this stuff with him/her. Otherwise, I'll try to keep on his good work and raise it to a higher level.