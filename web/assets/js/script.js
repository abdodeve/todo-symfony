/*!
 * Script for TodoApp
 * Copyright 2020 :)
 * Licensed under MIT - Feel free :)
 */


$(document).ready(function(){
    
    // onClick on delete btn in todolist (left block)
    $( document ).on( "click", ".btn_delete_todoList", function(e) {
        e.preventDefault();

        var $el = $(this);
        var todolist_id = this.getAttribute('data-todoList-id');
        var url = window.location.origin+"/TodoList/delete" ;
        $.confirm({
            title: 'Confirmation!',
            content: 'Êtes-vous sûr de vouloir supprimer ce ?',
            buttons: {

                yes: {
                    text: 'Oui', // With spaces and symbols
                    action: function () {
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {data: { ids: todolist_id}},
                            beforeSend: function() {
                                console.log("beforeSend");
                            },
                            success: function(data) {
                                console.log("success", data.isDeleted);
                                if(data.isDeleted == true){
                                    $el.closest( "li" ).remove();
                                }
                            },
                            error: function(xhr) { // if error occured
                                console.log("error", xhr);
                            },
                            complete: function() {
                                console.log("complete");
                            },
                        });
                    }
                },
                no: {
                    text: 'Non', // With spaces and symbols
                    action: function () {
                        $.alert('You clicked on "Non"');
                    }
                },
                cancel:  {
                    text: 'Annuler', // With spaces and symbols
                    action: function () {
                        $.alert('You clicked on "Annuler"');
                    }
                },
            }
        });
                  
    });

});