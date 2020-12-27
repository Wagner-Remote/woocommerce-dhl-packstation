(function(jQuery) {

    var shipToDifferentAddressLabel = jQuery('#ship-to-different-address label span');

    var data = {
        'action': 'packstation_availability_check',
        'check': true
    };

    jQuery.post(ajaxurl, data, function(response) {
        console.log(response);
        var obj = jQuery.parseJSON( response );
        console.log(obj);
        if( 1 == obj.available ){
            changeShipToDifferentAddressLabel();
        }       
    });

    var changeShipToDifferentAddressLabel = function(){
        jQuery(shipToDifferentAddressLabel).html('Lieferung an eine andere Adresse oder <i class="packstation-label"><span>Packstation</span></i>?')
    }

})( jQuery );