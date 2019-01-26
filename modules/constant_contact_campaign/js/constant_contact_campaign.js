(function ($) {
  'use strict';

  Drupal.behaviors.constant_contact_campaign = {
    attach: function (context, settings) {

      $(".campaign_status input").on('change', function () {
        if(this.checked){
          console.log('checked...')
          $("input[type='submit']").val("Post campaign");
        }
        else{
          console.log('unchecked...')
          $("input[type='submit']").val("Save campaign");
        }
      });
    }
  };
}(jQuery));
