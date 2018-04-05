<?php
/**
 * Array of plugin options
 */

$options = array();

$options['general'][] = array(
    "name"  => __( "Cache results", "aws" ),
    "desc"  => __( "Turn off if you have old data in the search results after content of products was changed.<br><strong>CAUTION:</strong> can dramatically increase search speed", "aws" ),
    "id"    => "cache",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'aws' ),
        'false'  => __( 'Off', 'aws' ),
    )
);

$options['general'][] = array(
    "name"  => __( "Search in", "aws" ),
    "desc"  => __( "Search source: Drag&drop sources order to change priority, or exclude by moving to deactivated sources.", "aws" ),
    "id"    => "search_in",
    "value" => "title,content,sku,excerpt",
    "choices" => array( "title", "content", "sku", "excerpt", "category", "tag" ),
    "type"  => "sortable"
);

$options['general'][] = array(
    "name"  => __( "Show out-of-stock", "aws" ),
    "desc"  => __( "Show out-of-stock products in search", "aws" ),
    "id"    => "outofstock",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'Show', 'aws' ),
        'false'  => __( 'Hide', 'aws' ),
    )
);

$options['general'][] = array(
    "name"  => __( "Stop words list", "aws" ),
    "desc"  => __( "Comma separated list of words that will be excluded from search.", "aws" ) . '<br>' . __( "Re-index required on change.", "aws" ),
    "id"    => "stopwords",
    "value" => "a, about, above, across, after, afterwards, again, against, all, almost, alone, along, already, also, although, always, am, among, amongst, amoungst, amount, an, and, another, any, anyhow, anyone, anything, anyway, anywhere, are, around, as, at, back, be, became, because, become, becomes, becoming, been, before, beforehand, behind, being, below, beside, besides, between, beyond, bill, both, bottom, but, by, call, can, cannot, cant, co, con, could, couldnt, cry, de, describe, detail, do, done, down, due, during, each, eg, eight, either, eleven, else, elsewhere, empty, enough, etc, even, ever, every, everyone, everything, everywhere, except, few, fifteen, fify, fill, find, fire, first, five, for, former, formerly, forty, found, four, from, front, full, further, get, give, go, had, has, hasnt, have, he, hence, her, here, hereafter, hereby, herein, hereupon, hers, herself, him, himself, his, how, however, hundred, ie, if, in, inc, indeed, interest, into, is, it, its, itself, keep, last, latter, latterly, least, less, ltd, made, many, may, me, meanwhile, might, mill, mine, more, moreover, most, mostly, move, much, must, my, myself, name, namely, neither, never, nevertheless, next, nine, no, nobody, none, noone, nor, not, nothing, now, nowhere, of, off, often, on, once, one, only, onto, or, other, others, otherwise, our, ours, ourselves, out, over, own, part, per, perhaps, please, put, rather, re, same, see, seem, seemed, seeming, seems, serious, several, she, should, show, side, since, sincere, six, sixty, so, some, somehow, someone, something, sometime, sometimes, somewhere, still, such, system, take, ten, than, that, the, their, them, themselves, then, thence, there, thereafter, thereby, therefore, therein, thereupon, these, they, thickv, thin, third, this, those, though, three, through, throughout, thru, thus, to, together, too, top, toward, towards, twelve, twenty, two, un, under, until, up, upon, us, very, via, was, we, well, were, what, whatever, when, whence, whenever, where, whereafter, whereas, whereby, wherein, whereupon, wherever, whether, which, while, whither, who, whoever, whole, whom, whose, why, will, with, within, without, would, yet, you, your, yours, yourself, yourselves",
    "type"  => "textarea"
);

$options['general'][] = array(
    "name"  => __( "Use Google Analytics", "aws" ),
    "desc"  => __( "Use google analytics to track searches. You need google analytics to be installed on your site.", "aws" ) . '<br>' . __( "Will send event with category - 'AWS search', action - 'AWS Search Term' and label of value of search term.", "aws" ),
    "id"    => "use_analytics",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'aws' ),
        'false'  => __( 'Off', 'aws' ),
    )
);

// Search Form Settings
$options['form'][] = array(
    "name"  => __( "Text for search field", "aws" ),
    "desc"  => __( "Text for search field placeholder.", "aws" ),
    "id"    => "search_field_text",
    "value" => __( "Search", "aws" ),
    "type"  => "text"
);

$options['form'][] = array(
    "name"  => __( "Nothing found field", "aws" ),
    "desc"  => __( "Text when there is no search results. HTML tags is allowed.", "aws" ),
    "id"    => "not_found_text",
    "value" => __( "Nothing found", "aws" ),
    "type"  => "textarea"
);

$options['form'][] = array(
    "name"  => __( "Minimum number of characters", "aws" ),
    "desc"  => __( "Minimum number of characters required to run ajax search.", "aws" ),
    "id"    => "min_chars",
    "value" => 1,
    "type"  => "number"
);

$options['form'][] = array(
    "name"  => __( "Show loader", "aws" ),
    "desc"  => __( "Show loader animation while searching.", "aws" ),
    "id"    => "show_loader",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'aws' ),
        'false' => __( 'Off', 'aws' ),
    )
);

$options['form'][] = array(
    "name"  => __( "Search Results Page", "aws" ),
    "desc"  => __( "Go to search results page when user clicks 'Enter' key on search form?", "aws" ),
    "id"    => "show_page",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'aws' ),
        'false' => __( 'Off', 'aws' )
    )
);

// Search Results Settings

$options['results'][] = array(
    "name"  => __( "Show image", "aws" ),
    "desc"  => __( "Show product image for each search result.", "aws" ),
    "id"    => "show_image",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'aws' ),
        'false'  => __( 'Off', 'aws' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show description", "aws" ),
    "desc"  => __( "Show product description for each search result.", "aws" ),
    "id"    => "show_excerpt",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'aws' ),
        'false'  => __( 'Off', 'aws' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Description source", "aws" ),
    "desc"  => __( "From where to take product description.<br>If first source is empty data will be taken from other sources.", "aws" ),
    "id"    => "desc_source",
    "value" => 'content',
    "type"  => "radio",
    'choices' => array(
        'content'  => __( 'Content', 'aws' ),
        'excerpt'  => __( 'Excerpt', 'aws' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Description length", "aws" ),
    "desc"  => __( "Maximal allowed number of words for product description.", "aws" ),
    "id"    => "excerpt_length",
    "value" => 20,
    "type"  => "number"
);

$options['results'][] = array(
    "name"  => __( "Description content", "aws" ),
    "desc"  => __( "What to show in product description?", "aws" ),
    "id"    => "mark_words",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( "Smart scrapping sentences with searching terms from product description.", "aws" ),
        'false' => __( "First N words of product description ( number of words that you choose below. )", "aws" ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show price", "aws" ),
    "desc"  => __( "Show product price for each search result.", "aws" ),
    "id"    => "show_price",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'aws' ),
        'false' => __( 'Off', 'aws' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show categories", "aws" ),
    "desc"  => __( "Include categories in search result.", "aws" ),
    "id"    => "show_cats",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'aws' ),
        'false' => __( 'Off', 'aws' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show tags", "aws" ),
    "desc"  => __( "Include tags in search result.", "aws" ),
    "id"    => "show_tags",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'aws' ),
        'false' => __( 'Off', 'aws' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show sale badge", "aws" ),
    "desc"  => __( "Show sale badge for products in search results.", "aws" ),
    "id"    => "show_sale",
    "value" => 'true',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'aws' ),
        'false' => __( 'Off', 'aws' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Show product SKU", "aws" ),
    "desc"  => __( "Show product SKU in search results.", "aws" ),
    "id"    => "show_sku",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'aws' ),
        'false' => __( 'Off', 'aws' ),
    )
);


$options['results'][] = array(
    "name"  => __( "Show stock status", "aws" ),
    "desc"  => __( "Show stock status for every product in search results.", "aws" ),
    "id"    => "show_stock",
    "value" => 'false',
    "type"  => "radio",
    'choices' => array(
        'true'  => __( 'On', 'aws' ),
        'false' => __( 'Off', 'aws' ),
    )
);

$options['results'][] = array(
    "name"  => __( "Max number of results", "aws" ),
    "desc"  => __( "Maximum number of displayed search results.", "aws" ),
    "id"    => "results_num",
    "value" => 10,
    "type"  => "number"
);