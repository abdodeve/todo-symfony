/*!
 * Script for TodoApp
 * Copyright 2020 :)
 * Licensed under MIT - Feel free :)
 */


$(document).ready(function(){
    
    // onClick on delete btn in todolist (left block)
    $( document ).on( "click", ".btn_delete_todoList", function(e) {
        e.preventDefault();
        todolist_id = this.getAttribute('data-todoList-id');
        var url = window.location.origin+"/TodoList/delete" ;
        $.ajax({
            type: 'POST',
            url: url,
            data: {ids: "123"},
            beforeSend: function() {
                console.log("beforeSend");
            },
            success: function(data) {
                console.log("success", data);
            },
            error: function(xhr) { // if error occured
                console.log("error", xhr);
            },
            complete: function() {
                console.log("complete");
            },
        });
        console.log( "works !" );
    });

});