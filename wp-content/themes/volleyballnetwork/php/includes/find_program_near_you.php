<?php
   function find_program_near_you() {

      $HTML = '';
      $HTML .= '<section id="find-program-near-you-section" class="blue-bg template-section">
                  <center class="container">
                     <form>
                        <label class="white">Find A Program Near You</label>
                        <select id="find-program-select">
                           <option value="">Select Province</option>';
                              // select field for all state taxonomies
                              $states = get_terms(array(
                                    'taxonomy' => 'state',
                                    'orderby' => 'name',
                                    'hide_empty' => false
                              ));
                              foreach ($states as $state) {
                                    $HTML .= '<option value="' . $state->name . '">' . $state->name . '</option>';
                              }
                        $HTML .= '</select>
                        <a href="/program-search/?state=' . getUserProvince() . '" id="find-program-button" class="btn white">Search Now</a>
                     </form>
                  </center>
               </section>';

      echo $HTML;    
   }
?>