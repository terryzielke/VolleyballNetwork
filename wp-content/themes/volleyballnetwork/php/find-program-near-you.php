<section id="find-program-near-you-section" class="blue-bg">
	<center class="container">
      <form>
         <label class="white">Find A Program Near You</label>
         <?php /* <input type="text" name="zip" placeholder="<?=get_user_postal_code()?>"> */ ?>
         <select id="find-program-select">
            <option value="">Select Province</option>
         <?php
            // select field for all state taxonomies
            $states = get_terms(array(
                  'taxonomy' => 'state',
                  'orderby' => 'name',
                  'hide_empty' => false
            ));
            foreach ($states as $state) {
                  echo '<option value="' . $state->name . '">' . $state->name . '</option>';
            }

         ?>
         </select>
         <a href="/program-search/?state=<?=getUserProvince()?>" id="find-program-button" class="btn white">Search Now</a>
      </form>
   </center>
</section>