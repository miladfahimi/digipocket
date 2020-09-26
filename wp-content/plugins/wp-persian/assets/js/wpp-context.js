jQuery(document).ready(function() {

    //jQuery("input[name='order_date']").removeAttr('pattern');
    //jQuery("input[name='order_date_hour']").removeAttr('pattern');
    //jQuery("input[name='order_date_minute']").removeAttr('pattern');

    function isUnicode(str) {
        var letters = [];
        for (var i = 0; i <= str.length; i++) {
            letters[i] = str.substring((i - 1), i);
            if (letters[i].charCodeAt(0) > 255) { return true; }
        }
        return false;
    }

    var prevKey=null;


    jQuery(":text, textarea").keydown(function(e) {
        if(prevKey && prevKey.ctrlKey && e.originalEvent.code==="ShiftRight") {
            jQuery(this).css('direction', 'rtl');
            jQuery(this).css('text-align', 'right');
            jQuery(this).attr('dir','rtl');
            prevKey=null;
        }else if(prevKey && prevKey.ctrlKey && e.originalEvent.code==="ShiftLeft") {
            jQuery(this).css('direction', 'ltr');
            jQuery(this).css('text-align', 'left');
            jQuery(this).attr('dir','ltr');
            prevKey=null;
        }
        prevKey = e;
    });


    jQuery(":text, textarea").keyup(function(e) {
        //console.log(e);
        if(e.keyCode>48 ){
            if (isUnicode(jQuery(this).val())) {
                jQuery(this).css('direction', 'rtl');
                jQuery(this).attr('dir','rtl');
            }
            else {
                jQuery(this).css('direction', 'ltr');
                jQuery(this).attr('dir','ltr');
            }
        }
        //if(e.shiftKey && e.keyCode==32){
///            console.log('shift+');
//        }

    });

/*
    function transformTypedCharacter(typedChar) {
        return typedChar == "a" ? "b" : typedChar;
    }

    function insertTextAtCursor(text) {
        var sel, range, textNode;
        if (window.getSelection) {
            sel = window.getSelection();
            if (sel.getRangeAt && sel.rangeCount) {
                range = sel.getRangeAt(0).cloneRange();
                range.deleteContents();
                textNode = document.createTextNode(text);
                range.insertNode(textNode);

                // Move caret to the end of the newly inserted text node
                range.setStart(textNode, textNode.length);
                range.setEnd(textNode, textNode.length);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        } else if (document.selection && document.selection.createRange) {
            range = document.selection.createRange();
            range.pasteHTML(text);
        }
    }

    jQuery(":text").keypress(function(evt) {
        console.log(evt);
        if(evt.shiftKey && evt.keyCode==32){
            insertTextAtCursor('s');
            return false;
        }
        else if (evt.which) {
            var charStr = String.fromCharCode(evt.which);
            var transformedChar = transformTypedCharacter(charStr);
            if (transformedChar != charStr) {
                insertTextAtCursor(transformedChar);
                return false;
            }
        }
    });
*/
    jQuery.each(jQuery(":text, textarea"), function() {
        var letters = jQuery(this).val();
        if(letters.length > 0 && (letters.charCodeAt(0) > 256 || letters.charCodeAt(2) > 256 || letters.charCodeAt(letters.length - 1) > 256) ) {
            jQuery(this).css('direction', 'rtl');
        }
    });


    jQuery.each(jQuery("textarea"), function(){
        var letters = jQuery(this).val();
        if(letters.length > 0 && (letters.charCodeAt(0) > 256 || letters.charCodeAt(letters.length - 1) > 256) ) {

            jQuery(this).css('direction', 'rtl');
        }
    });


});// ready Document