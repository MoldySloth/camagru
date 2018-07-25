<?php

// function to sanitize data so that we can output data. stored in a database, santize when going in and escape when going out
// escaping the string that we pass in to make it secure 
// Ent_QUOTE for single and double quotes and then the character in codeing

function escape($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}