(function ($) {
  'use strict';

  Drupal.behaviors.constant_contact_block_unsubscribe = {
    attach: function (context, settings) {

      /*$( ".cc_block_my_lists input" ).on('change', function (event) {
        event.stopImmediatePropagation();
        if ($(this).is(':checked')){
          $('.cc_block_subscribe input:radio[name=subscribe_me][value=ACTIVE]').prop('checked',true);
          $('.unsubscribe').hide();
        }
        else{
          console.log('I Need to be removed from list');
          $('.cc_block_subscribe input:radio[name=subscribe_me][value=REMOVED]').prop('checked',true);
          $('.unsubscribe').show();
        }
      });

      //handles unsubscribe when active
      $( ".cc_block_subscribe input:radio[name=subscribe_me][value=ACTIVE]" ).on('click', function(event){
        event.stopImmediatePropagation();
        $( ".cc_block_my_lists input" ).prop('checked',true);
        $('.unsubscribe').hide();
      });
      $('.cc_block_subscribe input:radio[name=subscribe_me][value=REMOVED]').on('click', function (event) {
        event.stopImmediatePropagation();
        $('.unsubscribe legend span').addClass("js-form-required form-required");
        $('.unsubscribe').show();

      });*/
    }
  };
}(jQuery));
