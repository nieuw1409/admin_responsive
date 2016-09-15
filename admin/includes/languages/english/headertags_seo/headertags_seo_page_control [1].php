<?php
$contactHelp = file_get_contents(DIR_WS_LANGUAGES . $language . '/headertags_seo/headertags_seo_footer.txt');

define('HTS_HEADING', 'Page Control');
define('HTS_TEXT_MAIN', '
<div class="section" style="padding-top:10px;"><span class="subHeading">Layout</span> - Page Control is made up of two major sections.
The left section controls the the titles and tags for individual pages while the right sections controls the
global options.</div>

<div class="section"><span class="subHeading">Using the Left section</span> - Before anything can be done in this
section, a page must be selected from the dropdown list. The list should display most of the files that are in
the root of your shop that are meant for displaying on the web. There is some code built in that will prevent some
pages from displaying, like those that are meant to be secure, since the search engines can\t reach them which means
it doesn\'t serve any purpose to have specific titles and tags. </div>

<div style="padding:5px 0;">Once a page is selected, the text needs to be added into the root boxes, if they are to be used on the selected page.
For example, for the index.php file, which controls the home page, there should be text entered in the root box
(see the included image for the locations of these boxes). But if those boxes are filled in for the product_info.php
file, which handles all product pages, then the same text would appear on every product page, which is usually not desireable.
</div>  

<div style="padding:5px 0;">The check boxes below the root boxes are for determining which parts appear on the page. For example, the index.php file
controls the categories and manufacturers so those check boxes have to be checked for that page or the categories and
manufacturers won\'t have their own title and tags. The larger box beside the check boxes is the sort order box. It determines
in which order the selected items are to be displayed. So if categories is checked and set to sort order 2 and root is cheked 
and set to sort order 5, then the category text will appear before the root text on the page. In most cases, it is usually
not a good idea to use the default check boxes for the selected page.
</div>

<div class="section"><span class="subHeading" style="padding-top:10px;">Using the Right section</span> - The right section is 
made up of the Default section and the Pseudo Page section. The default title and tags are used on pages that don\t have their
own titles and tags set up in the left section. The code will build its own title and tags when needed so this section is seldom
necessary. Though valid text, in relation to the site, should be entered just in case. 
</div>

<div>The pseudo section is used to enter in pages that are created with one of the addons that allows the creation of
pages like, Article Manager, Information Pages and Page Manager. It should work with any such addon as long as the 
partial url is entered correctly. See that box for an example.
</div>

' .$contactHelp
);