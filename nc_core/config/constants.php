<?
//Constants//

//Functions that can be called by regexFindCode(). Be careful, functions allowed by this list
//can be called by any authorized user within pages using the [function:parameter] syntax.
const allowForEmbed = array('image','gallery','snippet');
//Nebula CMS Codes and BBCode (such as [b] [/b]) that are enabled in user generated text blocks.
const allowedHtml = array('b','i', 'u', 's', 'url', 'quote', 'code', 'color', 'list');
?>