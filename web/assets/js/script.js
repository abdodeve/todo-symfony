/*!
 * Script for TodoApp
 * Copyright 2020 :)
 * Licensed under MIT - Feel free :)
 */


$(document).ready(function(){
    
    // onClick on delete btn in todolist (left block)
    $( document ).on( "click", ".btn_delete_todoList", function(e) {
       // $(this).prevent();
        console.log( "works !" );
        return false ;
    });

});