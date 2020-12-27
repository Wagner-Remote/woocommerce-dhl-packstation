<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function tim_packstation_popup(){
    ?>

    <div id="tim-packstation-popup-bg"></div>
    <div id="tim-packstation-popup">
        <h2>Packstation finden</h2>
        <form id="packstation-finder-address">
            <label for="packstation-finder-address-input">
                Postleitzahl eingeben
             </label>
            <input type="text" id="packstation-finder-address-input" />
            <input type = "submit" value="Suchen"></input>
        </form>
        <div class="results" id="packstation-finder-results"></div>
    </div>

    <?php
}

add_action('wp_footer', 'tim_packstation_popup');